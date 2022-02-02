<?php

namespace DD\ContactListTest;

use DD\ContactList\Config\AppConfig;
use DD\ContactList\Infrastructure\DI\Container;
use DD\ContactList\Infrastructure\DI\ContainerInterface;
use DD\ContactList\Infrastructure\Http\ServerRequest;
use DD\ContactList\Infrastructure\HttpApplication\App;
use DD\ContactList\Infrastructure\Logger\Adapter\NullAdapter;
use DD\ContactList\Infrastructure\Logger\AdapterInterface;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;
use DD\ContactList\Infrastructure\Router\RouterInterface;
use DD\ContactList\Infrastructure\Uri\Uri;
use DD\ContactList\Infrastructure\View\NullRender;
use DD\ContactList\Infrastructure\View\RenderInterface;
use JsonException;
use PHPUnit\Framework\TestCase;

/**
 * Тестирование приложения
 */
class AppTest extends TestCase
{
    /**
     * Поставщик данных для тестирования приложения
     *
     * @return array
     */
    public static function dataProvider(): array
    {
        $diConfig = require __DIR__ . '/../config/dev/di.php';
        $diConfig['services'][AdapterInterface::class] = [
            'class' => NullAdapter::class
        ];
        $diConfig['services'][RenderInterface::class] = [
            'class' => NullRender::class
        ];

        return [
            'Тестирование поиска получателя по id' => [
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
                    ]
                ]
            ],

            'Тестирование поиска получателя по full_name' => [
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
                    ]
                ]
            ],

            'Тестирование ситуации когда данные о получателях не корректны. Нет поля birthday' => [
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

            'Тестирование ситуации с некорректными данными конфига приложения' => [
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

            'Тестирование ситуации с некорректным путем до файла с получателями' => [
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

            'Тестирование ситуации с некорректным путем до файла с клиентами' => [
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

            'Тестирование ситуации когда данные о клиентах некорректны. Нет поля id_recipient' => [
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
     * Запуск теста приложения
     *
     * @param array $in
     * @param array $out
     *
     * @dataProvider dataProvider
     *
     * @return void
     * @throws JsonException
     */
    public function testApp(array $in, array $out): void
    {
        //Arrange
        $httpRequest = new ServerRequest(
            'GET',
            '1.1',
            $in['uri'],
            Uri::createFromString($in['uri']),
            ['Content-Type' => 'application/json'],
            null
        );
        $diConfig = $in['diConfig'];
        $app = new App(
            static function (ContainerInterface $di): RouterInterface {
                return $di->get(RouterInterface::class);
            },
            static function (ContainerInterface $di): LoggerInterface {
                return $di->get(LoggerInterface::class);
            },
            static function (ContainerInterface $di): AppConfig {
                return $di->get(AppConfig::class);
            },
            static function (ContainerInterface $di): RenderInterface {
                return $di->get(RenderInterface::class);
            },
            static function () use ($diConfig): ContainerInterface {
                return Container::createFromArray($diConfig);
            }
        );

        //Action
        $httpResponse = $app->dispatch($httpRequest);

        //Assert
        $this->assertEquals($out['httpCode'], $httpResponse->getStatusCode(), 'код ответа');
        $this->assertEquals(
            $out['result'],
            json_decode($httpResponse->getBody(), true, 512, JSON_THROW_ON_ERROR),
            'Cтруктура ответа'
        );
    }
}
