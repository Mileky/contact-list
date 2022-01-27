<?php

use DD\ContactList;
use DD\ContactList\Controller\GetContactsCollectionController;
use DD\ContactList\Controller\GetContactsController;
use DD\ContactList\Config\AppConfig;
use DD\ContactList\Infrastructure\DI\ContainerInterface;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;
use DD\ContactList\Infrastructure\Router\ChainRouters;
use DD\ContactList\Infrastructure\Router\ControllerFactory;
use DD\ContactList\Infrastructure\Router\DefaultRouter;
use DD\ContactList\Infrastructure\Router\RegExpRouter;
use DD\ContactList\Infrastructure\Router\RouterInterface;
use DD\ContactList\Infrastructure\Router\UniversalRouter;
use DD\ContactList\Infrastructure\Uri\Uri;


return [
    'instances' => [
        'controllerNs'   => 'DD\\ContactList\\Controller',
        'handlers'       => require __DIR__ . '/../request.handlers.php',
        'regExpHandlers' => require __DIR__ . '/../regExp.handlers.php',
        'appConfig'      => require __DIR__ . '/config.php',
    ],
    'services'  => [

        ContactList\Infrastructure\Console\Output\OutputInterface::class => [
            'class' => ContactList\Infrastructure\Console\Output\EchoOutput::class
        ],

        ContactList\ConsoleCommand\FindContacts::class => [
            'args' => [
                'output'               => ContactList\Infrastructure\Console\Output\OutputInterface::class,
                'searchContactService' => ContactList\Service\SearchContactService::class
            ]
        ],

        ContactList\ConsoleCommand\HashStr::class => [
            'args' => [
                'output' => ContactList\Infrastructure\Console\Output\OutputInterface::class
            ]
        ],

        GetContactsCollectionController::class => [
            'args' => [
                'logger'               => LoggerInterface::class,
                'searchContactService' => ContactList\Service\SearchContactService::class
            ]
        ],
        GetContactsController::class           => [
            'args' => [
                'logger'               => LoggerInterface::class,
                'searchContactService' => ContactList\Service\SearchContactService::class
            ]
        ],

        ContactList\Controller\UpdateContactListController::class => [
            'args' => [
                'addBlacklistContactService' => ContactList\Service\AddBlacklistContactService::class
            ]
        ],

        ContactList\Controller\CreateAddressController::class => [
            'args' => [
                'arrivalNewAddressService' => ContactList\Service\ArrivalNewAddressService::class
            ]
        ],

        ContactList\Service\SearchContactService::class       => [
            'args' => [
                'logger'            => LoggerInterface::class,
                'contactRepository' => ContactList\Entity\ContactRepositoryInterface::class
            ]
        ],
        ContactList\Service\AddBlacklistContactService::class => [
            'args' => [
                'contactListRepository' => ContactList\Entity\ContactListRepositoryInterface::class
            ]
        ],

        ContactList\Service\ArrivalNewAddressService::class => [
            'args' => [
                'addressRepository' => ContactList\Entity\AddressRepositoryInterface::class,
                'contactRepository' => ContactList\Entity\ContactRepositoryInterface::class
            ]
        ],

        ContactList\Service\SearchAddressService::class => [
            'args' => [
                'logger'            => LoggerInterface::class,
                'addressRepository' => ContactList\Entity\AddressRepositoryInterface::class
            ]
        ],

        ContactList\Infrastructure\ViewTemplate\ViewTemplateInterface::class => [
            'class' => ContactList\Infrastructure\ViewTemplate\PhtmlTemplate::class
        ],

        ContactList\Controller\AddressAdministrationController::class => [
            'args' => [
                'logger'                   => LoggerInterface::class,
                'arrivalNewAddressService' => ContactList\Service\ArrivalNewAddressService::class,
                'searchContactService'     => ContactList\Service\SearchContactService::class,
                'viewTemplate'             => ContactList\Infrastructure\ViewTemplate\ViewTemplateInterface::class,
                'addressService'           => ContactList\Service\SearchAddressService::class,
                'httpAuthProvider'         => ContactList\Infrastructure\Auth\HttpAuthProvider::class
            ]
        ],

        ContactList\Controller\LoginController::class => [
            'args' => [
                'viewTemplate'     => ContactList\Infrastructure\ViewTemplate\ViewTemplateInterface::class,
                'httpAuthProvider' => ContactList\Infrastructure\Auth\HttpAuthProvider::class
            ]
        ],

        ContactList\Infrastructure\DataLoader\DataLoaderInterface::class => [
            'class' => ContactList\Infrastructure\DataLoader\JsonDataLoader::class
        ],

        LoggerInterface::class => [
            'class' => ContactList\Infrastructure\Logger\Logger::class,
            'args'  => [
                'adapter' => ContactList\Infrastructure\Logger\AdapterInterface::class
            ]
        ],

        ContactList\Infrastructure\Logger\AdapterInterface::class => [
            'class' => ContactList\Infrastructure\Logger\Adapter\FileAdapter::class,
            'args'  => [
                'pathToFile' => 'pathToLogFile'
            ]
        ],

        ContactList\Infrastructure\View\RenderInterface::class => [
            'class' => ContactList\Infrastructure\View\DefaultRender::class
        ],

        ContactList\Entity\ContactRepositoryInterface::class => [
            'class' => ContactList\Repository\ContactJsonFileRepository::class,
            'args'  => [
                'dataLoader'       => ContactList\Infrastructure\DataLoader\DataLoaderInterface::class,
                'pathToRecipients' => 'pathToRecipients',
                'pathToCustomers'  => 'pathToCustomers',
                'pathToKinsfolk'   => 'pathToKinsfolk',
                'pathToColleagues' => 'pathToColleagues'
            ]
        ],

        ContactList\Infrastructure\Auth\UserDataStorageInterface::class => [
            'class' => ContactList\Repository\UserJsonFileRepository::class,
            'args'  => [
                'pathToUsers' => 'pathToUsers',
                'dataLoader'  => ContactList\Infrastructure\DataLoader\DataLoaderInterface::class
            ]
        ],

        ContactList\Entity\ContactListRepositoryInterface::class => [
            'class' => ContactList\Repository\ContactListRepository::class,
            'args'  => [
                'pathToContactList' => 'pathToContactList',
                'dataLoader'        => ContactList\Infrastructure\DataLoader\DataLoaderInterface::class,
                'contactRepository' => ContactList\Entity\ContactRepositoryInterface::class
            ]
        ],

        ContactList\Entity\AddressRepositoryInterface::class => [
            'class' => ContactList\Repository\AddressJsonFileRepository::class,
            'args'  => [
                'dataLoader'        => ContactList\Infrastructure\DataLoader\DataLoaderInterface::class,
                'pathToAddress'     => 'pathToAddress',
                'contactRepository' => ContactList\Entity\ContactRepositoryInterface::class
            ]
        ],

        ContactList\Infrastructure\Auth\HttpAuthProvider::class => [
            'args' => [
                'userDataStorage' => ContactList\Infrastructure\Auth\UserDataStorageInterface::class,
                'session'         => ContactList\Infrastructure\Session\SessionInterface::class,
                'loginUri'        => 'loginUri'
            ]
        ],

        RouterInterface::class   => [
            'class' => ChainRouters::class,
            'args'  => [
                RegExpRouter::class,
                DefaultRouter::class,
                UniversalRouter::class
            ]
        ],
        UniversalRouter::class   => [
            'args' => [
                'controllerFactory' => ControllerFactory::class,
                'controllerNs'      => 'controllerNs'
            ]

        ],
        DefaultRouter::class     => [
            'args' => [
                'handlers'          => 'handlers',
                'controllerFactory' => ControllerFactory::class
            ]
        ],
        ControllerFactory::class => [
            'args' => [
                'diContainer' => ContainerInterface::class
            ]
        ],
        RegExpRouter::class      => [
            'args' => [
                'handlers'          => 'regExpHandlers',
                'controllerFactory' => ControllerFactory::class
            ]
        ]

    ],
    'factories' => [
        ContainerInterface::class                                  => static function (ContainerInterface $c
        ): ContainerInterface {
            return $c;
        },
        'pathToLogFile'                                            => static function (ContainerInterface $c): string {
            /** @var \DD\ContactList\Config\AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToLogFile();
        },
        'pathToKinsfolk'                                           => static function (ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToKinsfolk();
        },
        'pathToColleagues'                                         => static function (ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToColleagues();
        },
        'pathToCustomers'                                          => static function (ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToCustomers();
        },
        'pathToRecipients'                                         => static function (ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToRecipients();
        },
        'pathToAddress'                                            => static function (ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToAddress();
        },
        'pathToContactList'                                        => static function (ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToContactList();
        },
        'pathToUsers'                                              => static function (ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToUsers();
        },
        'loginUri'                                                 => static function (ContainerInterface $c): Uri {
            $appConfig = $c->get(AppConfig::class);
            return Uri::createFromString($appConfig->getLoginUri());
        },
        AppConfig::class                                           => static function (ContainerInterface $c
        ): ContactList\Infrastructure\HttpApplication\AppConfigInterface {
            $appConfig = $c->get('appConfig');
            return AppConfig::createFromArray($appConfig);
        },
        ContactList\Infrastructure\Session\SessionInterface::class => static function (ContainerInterface $c) {
            return ContactList\Infrastructure\Session\SessionNative::create();
        },

    ],
];
