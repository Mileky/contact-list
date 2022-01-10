<?php

use DD\ContactList;
use DD\ContactList\Controller\GetColleaguesController;
use DD\ContactList\Controller\GetCustomersController;
use DD\ContactList\Controller\GetKinsfolkCollectionController;
use DD\ContactList\Controller\GetColleaguesCollectionController;
use DD\ContactList\Controller\GetCustomersCollectionController;
use DD\ContactList\Controller\GetKinsfolkController;
use DD\ContactList\Controller\GetRecipientsCollectionController;
use DD\ContactList\Controller\GetRecipientsController;
use DD\ContactList\Infrastructure\AppConfig;
use DD\ContactList\Infrastructure\DI\ContainerInterface;
use DD\ContactList\Infrastructure\Logger\FileLogger\Logger;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;
use DD\ContactList\Infrastructure\Router\ChainRouters;
use DD\ContactList\Infrastructure\Router\ControllerFactory;
use DD\ContactList\Infrastructure\Router\DefaultRouter;
use DD\ContactList\Infrastructure\Router\RegExpRouter;
use DD\ContactList\Infrastructure\Router\RouterInterface;
use DD\ContactList\Infrastructure\Router\UniversalRouter;


return [
    'instances' => [
        'controllerNs' => 'DD\\ContactList\\Controller',
        'handlers' => require __DIR__ . '/../request.handlers.php',
        'regExpHandlers' => require __DIR__ . '/../regExp.handlers.php',
        'appConfig' => require __DIR__ . '/config.php',
    ],
    'services' => [
        GetKinsfolkCollectionController::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'pathToKinsfolk' => 'pathToKinsfolk'
            ]
        ],
        GetKinsfolkController::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'pathToKinsfolk' => 'pathToKinsfolk'
            ]
        ],

        GetColleaguesCollectionController::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'pathToColleagues' => 'pathToColleagues'
            ]
        ],
        GetColleaguesController::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'pathToColleagues' => 'pathToColleagues'
            ]
        ],

        GetCustomersCollectionController::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'pathToCustomers' => 'pathToCustomers'
            ]
        ],
        GetCustomersController::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'pathToCustomers' => 'pathToCustomers'
            ]
        ],

        GetRecipientsCollectionController::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'pathToRecipients' => 'pathToRecipients'
            ]
        ],
        GetRecipientsController::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'pathToRecipients' => 'pathToRecipients'
            ]
        ],

        LoggerInterface::class => [
            'class' => Logger::class,
            'args' => [
                'pathToFile' => 'pathToLogFile'
            ]
        ],
        ContactList\Infrastructure\View\RenderInterface::class => [
            'class' => ContactList\Infrastructure\View\DefaultRender::class
        ],

        RouterInterface::class => [
            'class' => ChainRouters::class,
            'args' => [
                RegExpRouter::class,
                DefaultRouter::class,
                UniversalRouter::class
            ]
        ],
        UniversalRouter::class => [
            'args' => [
                'controllerFactory' => ControllerFactory::class,
                'controllerNs' => 'controllerNs'
            ]

        ],
        DefaultRouter::class => [
            'args' => [
                'handlers' => 'handlers',
                'controllerFactory' => ControllerFactory::class
            ]
        ],
        ControllerFactory::class => [
            'args' => [
                'diContainer' => ContainerInterface::class
            ]
        ],
        RegExpRouter::class => [
            'args' => [
                'handlers' => 'regExpHandlers',
                'controllerFactory' => ControllerFactory::class
            ]
        ]

    ],
    'factories' => [
        ContainerInterface::class => static function (ContainerInterface $c): ContainerInterface {
            return $c;
        },
        'pathToLogFile' => static function (ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToLogFile();
        },
        'pathToKinsfolk' => static function (ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToKinsfolk();
        },
        'pathToColleagues' => static function (ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToColleagues();
        },
        'pathToCustomers' => static function (ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToCustomers();
        },
        'pathToRecipients' => static function (ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToRecipients();
        },
        AppConfig::class => static function (ContainerInterface $c): AppConfig {
            $appConfig = $c->get('appConfig');
            return AppConfig::createFromArray($appConfig);
        }
    ],
];
