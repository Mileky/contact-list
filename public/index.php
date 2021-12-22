<?php

require_once __DIR__ . '/../src/Infrastructure/app.function.php';
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

use function DD\ContactList\Infrastructure\app;
use function DD\ContactList\Infrastructure\render;

$resultApp = (new App(
    include __DIR__ . '/../config/request.handlers.php',
    'DD\ContactList\Infrastructure\Logger\Factory::create',
    static function () {
        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
    }
))->dispatch($_SERVER['REQUEST_URI']);

render($resultApp['result'], $resultApp['httpCode']);