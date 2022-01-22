#!C:\php php
<?php

use DD\ContactList\Infrastructure\Autoloader;
use DD\ContactList\Infrastructure\Console\AppConsole;
use DD\ContactList\Infrastructure\Console\Output\OutputInterface;
use DD\ContactList\Infrastructure\Di\Container;
use DD\ContactList\Infrastructure\Di\ContainerInterface;

require_once __DIR__ . '/../src/Infrastructure/Autoloader.php';

spl_autoload_register(
    new Autoloader([
        '\\DD\\ContactList\\' => __DIR__ . '/../src/',
        '\\DD\\ContactListTest\\' => __DIR__ . '/../tests/'
    ])
);

(new AppConsole(
    require __DIR__ . '/../config/console.handlers.php',
    static function (ContainerInterface $di): OutputInterface {
        return $di->get(OutputInterface::class);
    },
    static function (): ContainerInterface {
        return Container::createFromArray(require __DIR__ . '/../config/dev/di.php');
    }
))->dispatch();
