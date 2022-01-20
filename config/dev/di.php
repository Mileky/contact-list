<?php

use DD\ContactList;
use DD\ContactList\Controller\GetContactsCollectionController;
use DD\ContactList\Controller\GetContactsController;
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

        ContactList\Infrastructure\Console\Output\OutputInterface::class => [
            'class' => ContactList\Infrastructure\Console\Output\EchoOutput::class
        ],

        ContactList\ConsoleCommand\FindContacts::class => [
            'args' => [
                'output' => ContactList\Infrastructure\Console\Output\OutputInterface::class,
                'searchContactService' => ContactList\Service\SearchContactService::class
            ]
        ],

        GetContactsCollectionController::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'searchContactService' => ContactList\Service\SearchContactService::class
            ]
        ],
        GetContactsController::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'searchContactService' => ContactList\Service\SearchContactService::class
            ]
        ],

        ContactList\Controller\UpdateContactListController::class => [
            'args' => [
                'addBlacklistContactService' => ContactList\Service\AddBlacklistContactService::class
            ]
        ],

        ContactList\Service\SearchContactService::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'contactRepository' => ContactList\Entity\ContactRepositoryInterface::class
            ]
        ],
        ContactList\Service\AddBlacklistContactService::class => [
            'args' => [
                'contactListRepository' => ContactList\Entity\ContactListRepositoryInterface::class
            ]
        ],
        ContactList\Infrastructure\DataLoader\DataLoaderInterface::class => [
            'class' => ContactList\Infrastructure\DataLoader\JsonDataLoader::class
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

        ContactList\Entity\ContactRepositoryInterface::class => [
            'class' => ContactList\Repository\ContactJsonFileRepository::class,
            'args' => [
                'dataLoader' => ContactList\Infrastructure\DataLoader\DataLoaderInterface::class,
                'pathToRecipients' => 'pathToRecipients',
                'pathToCustomers' => 'pathToCustomers',
                'pathToKinsfolk' => 'pathToKinsfolk',
                'pathToColleagues' => 'pathToColleagues'
            ]
        ],

        ContactList\Entity\ContactListRepositoryInterface::class => [
            'class' => ContactList\Repository\ContactListRepository::class,
            'args' => [
                'pathToContactList' => 'pathToContactList',
                'dataLoader' => ContactList\Infrastructure\DataLoader\DataLoaderInterface::class
            ]
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
        'pathToAddress' => static function (ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToAddress();
        },
        'pathToContactList' => static function (ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToContactList();
        },
        AppConfig::class => static function (ContainerInterface $c): AppConfig {
            $appConfig = $c->get('appConfig');
            return AppConfig::createFromArray($appConfig);
        }
    ],
];
