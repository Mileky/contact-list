<?php

require_once __DIR__ . '/../src/Infrastructure/app.function.php';
require_once __DIR__ . '/../src/Infrastructure/Autoloader.php';

use DD\ContactList\Infrastructure\App;
use DD\ContactList\Infrastructure\Autoloader;

spl_autoload_register(
    new Autoloader([
        '\\DD\\ContactList\\' => __DIR__ . '/../src/',
        '\\DD\\ContactListTest\\' => __DIR__ . '/../test/',
    ])
);

use DD\ContactList\Infrastructure\AppConfig;
use DD\ContactList\Infrastructure\Http\ServerRequest;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;
use DD\ContactList\Infrastructure\Uri\Uri;
use DD\ContactListTest\TestUtils;
use DD\ContactList\Infrastructure\Logger;

/**
 *  Тестирование приложения
 */
class UnitTest
{
    private static function testDataProvider(): array
    {
        $handlers = include __DIR__ . '/../config/request.handlers.php';
        $loggerFactory = static function (): LoggerInterface {
            return new Logger\NullLogger\Logger();
        };
        return [
            [
                'testName' => 'Тестирование поиска получателя по id',
                'in' => [
                    'handlers' => $handlers,
                    'uri' => '/recipient?id_recipient=1',
                    'loggerFactory' => $loggerFactory,
                    'appConfigFactory' => static function () {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['loggerType'] = 'echoLogger';
                        return AppConfig::createFromArray($config);
                    }
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
                    'handlers' => $handlers,
                    'uri' => '/recipient?full_name=Осипов Геннадий Иванович',
                    'loggerFactory' => $loggerFactory,
                    'appConfigFactory' => static function () {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['loggerType'] = 'echoLogger';
                        return AppConfig::createFromArray($config);
                    }
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
                    'handlers' => $handlers,
                    'uri' => '/recipient?full_name=Осипов Геннадий Иванович',
                    'loggerFactory' => $loggerFactory,
                    'appConfigFactory' => static function () {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToRecipients'] = __DIR__ . '/data/broken.recipient.json';
                        return AppConfig::createFromArray($config);
                    }
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
                    'handlers' => $handlers,
                    'uri' => '/recipient?id_recipient=1',
                    'loggerFactory' => $loggerFactory,
                    'appConfigFactory' => static function () {
                        return 'Ops!';
                    }
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect application config'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации с некорректным путем до файла с получателями',
                'in' => [
                    'handlers' => $handlers,
                    'uri' => '/recipient?id_recipient=1',
                    'loggerFactory' => $loggerFactory,
                    'appConfigFactory' => static function () {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToRecipients'] = __DIR__ . '/data/unknown.recipient.json';
                        return AppConfig::createFromArray($config);
                    }
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
                    'handlers' => $handlers,
                    'uri' => '/customers?id_recipient=7',
                    'loggerFactory' => $loggerFactory,
                    'appConfigFactory' => static function () {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToRecipients'] = __DIR__ . '/data/unknown.customer.json';
                        return AppConfig::createFromArray($config);
                    }
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
                    'handlers' => $handlers,
                    'uri' => '/customers?full_name=Калинин Пётр Александрович',
                    'loggerFactory' => $loggerFactory,
                    'appConfigFactory' => static function () {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToCustomers'] = __DIR__ . '/data/broken.customers.json';
                        return AppConfig::createFromArray($config);
                    }
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
            $httpResponse = (new App(
                $testItem['in']['handlers'],
                $testItem['in']['loggerFactory'],
                $testItem['in']['appConfigFactory'],

            ))->dispatch($httpRequest);

            //Assert
            if ($httpResponse->getStatusCode() === $testItem['out']['httpCode']) {
                echo "    OK --- код ответа\n";
            } else {
                echo "    FAIL - код ответа. Ожидалось: {$testItem['out']['httpCode']}. Актуальное значение: {$httpResponse->getStatusCode()}\n";
            }
            $actualResult = json_decode($httpResponse->getBody(), true, 512, JSON_THROW_ON_ERROR);

            //Лишние элементы
            $unnecessaryElements = TestUtils::arrayDiffAssocRecursive($actualResult, $testItem['out']['result']);
            //Недостающие элементы
            $missingElements = TestUtils::arrayDiffAssocRecursive($testItem['out']['result'], $actualResult);

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
                echo "    ОК - данные ответа валидны\n";
            } else {
                echo "    FAIL - данные ответа валидны\n" . $errMsg;
            }
        }
    }
}

UnitTest::runTest();