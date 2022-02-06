#!C:\php php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use DD\ContactList\Infrastructure\Console\AppConsole;
use DD\ContactList\Infrastructure\Console\Output\OutputInterface;
use DD\ContactList\Infrastructure\Di\Container;
use DD\ContactList\Infrastructure\Di\ContainerInterface;
use DD\ContactList\Infrastructure\DI\SymfonyDiContainerInit;

(new AppConsole(
    require __DIR__ . '/../config/console.handlers.php',
    static function (ContainerInterface $di): OutputInterface {
        return $di->get(OutputInterface::class);
    },
    new SymfonyDiContainerInit(
        __DIR__ . '/../config/dev/di.xml',
        [
            'kernel.project_dir' => __DIR__ . '/../'
        ],
        new SymfonyDiContainerInit\CacheParams(
            'DEV' !== getenv('ENV_TYPE'),
            __DIR__ . '/../var/cache/di-symfony/DDContactListCachedContainer.php'
        )
    )
))->dispatch();
