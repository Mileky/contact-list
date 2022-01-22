<?php

require_once __DIR__ . '/../src/Infrastructure/Autoloader/Autoloader.php';

use DD\ContactList\Infrastructure\HttpApplication\App;
use DD\ContactList\Infrastructure\Autoloader\Autoloader;

spl_autoload_register(
    new Autoloader([
        '\\DD\\ContactList\\' => __DIR__ . '/../src/',
        '\\DD\\ContactListTest\\' => __DIR__ . '/../test/',
    ])
);

use DD\ContactList\Config\AppConfig;
use DD\ContactList\Infrastructure\DI\Container;
use DD\ContactList\Infrastructure\Http\ServerRequest;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;
use DD\ContactList\Infrastructure\Router\RouterInterface;
use DD\ContactList\Infrastructure\Uri\Uri;
use DD\ContactList\Infrastructure\View\NullRender;
use DD\ContactList\Infrastructure\View\RenderInterface;
use DD\ContactListTest\UtilsTest;
use DD\ContactList\Infrastructure\Logger;

/**
 *  Тестирование приложения
 */
class UnitTest
{
    private static function testDataProvider(): array
    {
        $diConfig = require __DIR__ . '/../config/dev/di.php';
        $diConfig['services'][Logger\AdapterInterface::class] = [
            'class' => Logger\Adapter\NullAdapter::class
        ];
        $diConfig['services'][RenderInterface::class] = [
            'class' => NullRender::class
        ];

        return [
            [
                'testName' => 'Тестирование поиска получателя по id',
                'in' => [
                    'uri' => '/contacts?id_recipient=1',
                    'diConfig' => $diConfig
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id_recipient' => 1,
                            'full_name' => 'Осипов Геннадий Иванович',
                            'birthday' => '15.06.1985',
                            'profession' => 'Системный администратор'
                        ],
                    ],
                ]
            ],
            [
                'testName' => 'Тестирование поиска получателя по full_name',
                'in' => [
                    'uri' => '/contacts?full_name=Осипов Геннадий Иванович',
                    'diConfig' => $diConfig
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id_recipient' => 1,
                            'full_name' => 'Осипов Геннадий Иванович',
                            'birthday' => '15.06.1985',
                            'profession' => 'Системный администратор'
                        ],
                    ],
                ]
            ],
            [
                'testName' => 'Тестирование ситуации когда данные о получателях не корректны. Нет поля birthday',
                'in' => [
                    'uri' => '/contacts?full_name=Осипов Геннадий Иванович',
                    'diConfig' => (static function ($diConfig) {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToRecipients'] = __DIR__ . '/data/broken.recipient.json';
                        $diConfig['instances']['appConfig'] = $config;
                        return $diConfig;
                    })(
                        $diConfig
                    )
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Отсутствуют обязательные элементы: birthday'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации с некорректными данными конфига приложения',
                'in' => [
                    'uri' => '/contacts?id_recipient=1',
                    'diConfig' => (static function ($diConfig) {
                        $diConfig['factories'][AppConfig::class] = static function () {
                            return 'Oops';
                        };
                        return $diConfig;
                    })(
                        $diConfig
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'system error'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации с некорректным путем до файла с получателями',
                'in' => [
                    'uri' => '/contacts?id_recipient=1',
                    'diConfig' => (static function ($diConfig) {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToRecipients'] = __DIR__ . '/data/unknown.recipient.json';
                        $diConfig['instances']['appConfig'] = $config;
                        return $diConfig;
                    })(
                        $diConfig
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Некорректный путь до файла с данными'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации с некорректным путем до файла с клиентами',
                'in' => [
                    'uri' => '/contacts?id_recipient=7',
                    'diConfig' => (static function ($diConfig) {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToRecipients'] = __DIR__ . '/data/unknown.customer.json';
                        $diConfig['instances']['appConfig'] = $config;
                        return $diConfig;
                    })(
                        $diConfig
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Некорректный путь до файла с данными'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации когда данные о клиентах некорректны. Нет поля id_recipient',
                'in' => [

                    'uri' => '/contacts?full_name=Калинин Пётр Александрович',
                    'diConfig' => (static function ($diConfig) {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToCustomers'] = __DIR__ . '/data/broken.customers.json';
                        $diConfig['instances']['appConfig'] = $config;
                        return $diConfig;
                    })(
                        $diConfig
                    )
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Отсутствуют обязательные элементы: id_recipient'
                    ]
                ]
            ],
        ];
    }

    /**
     * Запускает тест
     *
     * @return void
     * @throws JsonException
     */
    public static function runTest(): void
    {
        foreach (static::testDataProvider() as $testItem) {
            echo "-----{$testItem['testName']}-----\n";

            $httpRequest = new ServerRequest(
                'GET',
                '1.1',
                $testItem['in']['uri'],
                Uri::createFromString($testItem['in']['uri']),
                ['Content-Type' => 'application/json'],
                null
            );

            //Arrange и Act
            $diConfig = $testItem['in']['diConfig'];

            $httpResponse = (new App(
                static function (Container $di): RouterInterface {
                    return $di->get(RouterInterface::class);
                },
                static function (Container $di): LoggerInterface {
                    return $di->get(LoggerInterface::class);
                },
                static function (Container $di): AppConfig {
                    return $di->get(AppConfig::class);
                },
                static function (Container $di): RenderInterface {
                    return $di->get(RenderInterface::class);
                },
                static function () use ($diConfig): Container {
                    return Container::createFromArray($diConfig);
                }
            ))->dispatch($httpRequest);

            //Assert
            if ($httpResponse->getStatusCode() === $testItem['out']['httpCode']) {
                echo "    OK --- код ответа\n";
            } else {
                echo "    FAIL - код ответа. Ожидалось: {$testItem['out']['httpCode']}. Актуальное значение: {$httpResponse->getStatusCode()}\n";
            }
            $actualResult = json_decode($httpResponse->getBody(), true, 512, JSON_THROW_ON_ERROR);

            //Лишние элементы
            $unnecessaryElements = UtilsTest::arrayDiffAssocRecursive($actualResult, $testItem['out']['result']);
            //Недостающие элементы
            $missingElements = UtilsTest::arrayDiffAssocRecursive($testItem['out']['result'], $actualResult);

            $errMsg = '';

            if (count($unnecessaryElements) > 0) {
                $errMsg .= sprintf(
                    "         Есть лишние элементы %s\n",
                    json_encode($unnecessaryElements, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)
                );
            }
            if (count($missingElements) > 0) {
                $errMsg .= sprintf(
                    "         Есть лишние недостающие элементы %s\n",
                    json_encode($missingElements, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)
                );
            }

            if ('' === $errMsg) {
                echo "    ОК --- данные ответа валидны\n";
            } else {
                echo "    FAIL - данные ответа валидны\n" . $errMsg;
            }
        }
    }
}

UnitTest::runTest();