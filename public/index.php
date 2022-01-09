<?php

require_once __DIR__ . '/../src/Infrastructure/Autoloader.php';


use DD\ContactList\Infrastructure\App;
use DD\ContactList\Infrastructure\Autoloader;

spl_autoload_register(
    new Autoloader([
        '\\DD\\ContactList\\' => __DIR__ . '/../src/',
        '\\DD\\ContactListTest\\' => __DIR__ . '/../tests/'
    ])
);

use DD\ContactList\Infrastructure\AppConfig;

use DD\ContactList\Infrastructure\Http\ServerRequestFactory;

use DD\ContactList\Infrastructure\View\DefaultRender;


$httpResponse = (new App(
    include __DIR__ . '/../config/request.handlers.php',
    'DD\ContactList\Infrastructure\Logger\Factory::create',
    static function () {
        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
    },
    static function () {
        return new DefaultRender();
    }
))->dispatch(ServerRequestFactory::createFromGlobals($_SERVER));
