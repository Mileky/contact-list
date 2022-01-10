<?php

use DD\ContactList;
use DD\ContactList\Controller\FindKinsfolk;
use DD\ContactList\Controller\FindColleagues;
use DD\ContactList\Controller\FindCustomers;
use DD\ContactList\Controller\FindRecipient;
use DD\ContactList\Infrastructure\AppConfig;
use DD\ContactList\Infrastructure\Di\ContainerInterface;
use DD\ContactList\Infrastructure\Logger\FileLogger\Logger;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;


return [
    'instances' => [
        'handlers' => require __DIR__ . '/../request.handlers.php',
        'appConfig' => require __DIR__ . '/config.php',
    ],
    'services' => [
        FindKinsfolk::class => [
            'args' => [
                'appConfig' => AppConfig::class,
                'logger' => LoggerInterface::class
            ]
        ],
        FindColleagues::class => [
            'args' => [
                'appConfig' => AppConfig::class,
                'logger' => LoggerInterface::class
            ]
        ],
        FindCustomers::class => [
            'args' => [
                'appConfig' => AppConfig::class,
                'logger' => LoggerInterface::class
            ]
        ],
        FindRecipient::class => [
            'args' => [
                'appConfig' => AppConfig::class,
                'logger' => LoggerInterface::class
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
    ],
    'factories' => [
        'pathToLogFile' => static function (ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToLogFile();
        },
        AppConfig::class => static function (ContainerInterface $c): AppConfig {
            $appConfig = $c->get('appConfig');
            return AppConfig::createFromArray($appConfig);
        }
    ],
];
