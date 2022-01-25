<?php

require_once __DIR__ . '/../vendor/autoload.php';


use DD\ContactList\Infrastructure\HttpApplication\App;
use DD\ContactList\Infrastructure\Autoloader\Autoloader;
use DD\ContactList\Config\AppConfig;
use DD\ContactList\Infrastructure\DI\Container;
use DD\ContactList\Infrastructure\Http\ServerRequestFactory;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;
use DD\ContactList\Infrastructure\Router\RouterInterface;
use DD\ContactList\Infrastructure\View\RenderInterface;

spl_autoload_register(
    new Autoloader([
        '\\DD\\ContactList\\' => __DIR__ . '/../src/',
        '\\DD\\ContactListTest\\' => __DIR__ . '/../tests/'
    ])
);



$httpResponse = (new App(
    static function (Container $di): RouterInterface {
        return $di->get(RouterInterface::class);
    },
    static function (Container $di): LoggerInterface {
        return $di->get(LoggerInterface::class);
    },
    static function (Container $di): AppConfig {
        return $di->get(AppConfig::class);
    },
    static function (Container $di): RenderInterface {
        return $di->get(RenderInterface::class);
    },
    static function (): Container {
        return Container::createFromArray(require __DIR__ . '/../config/dev/di.php');
    }
))->dispatch(ServerRequestFactory::createFromGlobals($_SERVER,  file_get_contents('php://input')));

