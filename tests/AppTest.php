<?php

namespace DD\ContactListTest;

use DD\ContactList\Config\AppConfig;
use DD\ContactList\Config\ContainerExtensions;
use DD\ContactList\Infrastructure\DI\ContainerInterface;
use DD\ContactList\Infrastructure\DI\SymfonyDiContainerInit;
use DD\ContactList\Infrastructure\DI\SymfonyDiContainerInit\ContainerParams;
use DD\ContactList\Infrastructure\HttpApplication\App;
use DD\ContactList\Infrastructure\Router\RouterInterface;
use DD\ContactList\Infrastructure\View\NullRender;
use DD\ContactList\Infrastructure\View\RenderInterface;
use Exception;
use JsonException;
use Nyholm\Psr7\ServerRequest;
use Nyholm\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Тестирование приложения
 */
class AppTest extends TestCase
{
    /**
     * Создание symfony di
     *
     * @return ContainerBuilder
     * @throws Exception
     */
    public static function createDiContainer(): ContainerBuilder
    {
        $containerBuilder = SymfonyDiContainerInit::createContainerBuilder(
            new ContainerParams(
                __DIR__ . '/../config/dev/di.xml',
                [
                    'kernel.project_dir' => __DIR__ . '/../'
                ],
                ContainerExtensions::httpAppContainerExtension()
            )
        );

        $containerBuilder->removeAlias(LoggerInterface::class);
        $containerBuilder->setDefinition(NullLogger::class, new Definition());
        $containerBuilder->setAlias(LoggerInterface::class, NullLogger::class)->setPublic(true);

        $containerBuilder->getDefinition(RenderInterface::class)
            ->setClass(NullRender::class)
            ->setArguments([]);

        return $containerBuilder;
    }

    /**
     * Метод используемый в тестах как иллюстрация некорректной работы фабрики
     *
     * @param array $config
     *
     * @return string
     */
    public static function bugFactory(array $config): string
    {
        return 'Oops';
    }

    /**
     * Поставщик данных для тестирования приложения
     *
     * @return array
     * @throws Exception
     */
    public static function dataProvider(): array
    {
        return [
            'Тестирование поиска клиента по id' => [
                'in' => [
                    'uri' => '/contacts?id_recipient=7',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id_recipient' => 7,
                            'full_name' => 'Калинин Пётр Александрович',
                            'birthday' => '04.06.1983',
                            'profession' => 'Фитнес тренер',
                            'contract_number' => '5684',
                            'average_transaction_amount' => '2500',
                            'discount' => '5%',
                            'time_to_call' => 'С 9:00 до 13:00 в будни'
                        ],
                    ]
                ]
            ],
            'Тестирование поиска коллеги по id' => [
                'in' => [
                    'uri' => '/contacts?id_recipient=10',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id_recipient' => 10,
                            'full_name' => 'Шатов Александр Иванович',
                            'birthday' => '02.12.1971',
                            'profession' => '',
                            'department' => 'Дирекция',
                            'position' => 'Директор',
                            'room_number' => '405'
                        ],
                    ]
                ]
            ],
            'Тестирование поиска родственника по id' => [
                'in' => [
                    'uri' => '/contacts?id_recipient=6',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id_recipient' => 6,
                            'full_name' => 'Дед',
                            'birthday' => '04.06.1945',
                            'profession' => 'Столяр',
                            'status' => 'Дед',
                            'ringtone' => 'Bells',
                            'hotkey' => '1'
                        ],
                    ]
                ]
            ],
            'Тестирование поиска знакомого по id' => [
                'in' => [
                    'uri' => '/contacts?id_recipient=1',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
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
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
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
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.config');
                        $appConfigParams['pathToRecipients'] = __DIR__ . '/data/broken.recipient.json';
                        $c->setParameter('app.config', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
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
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->getDefinition(AppConfig::class)->setFactory([AppTest::class, 'bugFactory']);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
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
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.config');
                        $appConfigParams['pathToRecipients'] = __DIR__ . '/data/unknown.recipient.json';
                        $c->setParameter('app.config', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
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
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.config');
                        $appConfigParams['pathToCustomers'] = __DIR__ . '/data/unknown.customers.json';
                        $c->setParameter('app.config', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
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
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.config');
                        $appConfigParams['pathToCustomers'] = __DIR__ . '/data/broken.customers.json';
                        $c->setParameter('app.config', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
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
            new Uri($in['uri']),
            ['Content-Type' => 'application/json']
        );

        $queryParams = [];
        parse_str($httpRequest->getUri()->getQuery(), $queryParams);
        $httpRequest = $httpRequest->withQueryParams($queryParams);

        $diContainer = $in['diContainer'];
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
            static function () use ($diContainer): ContainerInterface {
                return $diContainer;
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
