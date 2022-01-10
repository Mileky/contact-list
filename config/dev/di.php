<?php

use DD\ContactList;
use DD\ContactList\Controller\FindKinsfolk;
use DD\ContactList\Controller\FindColleagues;
use DD\ContactList\Controller\FindCustomers;
use DD\ContactList\Controller\FindRecipient;
use DD\ContactList\Infrastructure\AppConfig;
use DD\ContactList\Infrastructure\DI\ContainerInterface;
use DD\ContactList\Infrastructure\Logger\FileLogger\Logger;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;
use DD\ContactList\Infrastructure\Router\ChainRouters;
use DD\ContactList\Infrastructure\Router\ControllerFactory;
use DD\ContactList\Infrastructure\Router\DefaultRouter;
use DD\ContactList\Infrastructure\Router\RegExpRouter;
use DD\ContactList\Infrastructure\Router\RouterInterface;


return [
    'instances' => [
        'handlers' => require __DIR__ . '/../request.handlers.php',
        'appConfig' => require __DIR__ . '/config.php',
    ],
    'services' => [
        FindKinsfolk::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'pathToKinsfolk' => 'pathToKinsfolk'
            ]
        ],
        FindColleagues::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'pathToColleagues' => 'pathToColleagues'
            ]
        ],
        FindCustomers::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'pathToCustomers' => 'pathToCustomers'
            ]
        ],
        FindRecipient::class => [
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
                DefaultRouter::class
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
