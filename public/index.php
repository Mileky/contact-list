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
use DD\ContactList\Infrastructure\DI\Container;
use DD\ContactList\Infrastructure\Http\ServerRequestFactory;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;
use DD\ContactList\Infrastructure\View\RenderInterface;


//$httpResponse = (new App(
//    include __DIR__ . '/../config/request.handlers.php',
//    'DD\ContactList\Infrastructure\Logger\Factory::create',
//    static function () {
//        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
//    },
//    static function () {
//        return new DefaultRender();
//    }
//))->dispatch(ServerRequestFactory::createFromGlobals($_SERVER));

//$httpResponse = (static function (): App {
//    $sm = new ServiceManager(
//        [
//            'handlers' => require __DIR__ . '/../config/request.handlers.php',
//            'appConfig' => require __DIR__ . '/../config/dev/config.php'
//        ],
//        [
//            LoggerInterface::class => static function (ContainerInterface $container): LoggerInterface {
//                return Factory::create($container->get(AppConfig::class));
//            },
//            AppConfig::class => static function (ContainerInterface $container): AppConfig {
//                return AppConfig::createFromArray($container->get('appConfig'));
//            },
//            RenderInterface::class => static function (): RenderInterface {
//                return new DefaultRender();
//            }
//        ]
//    );
//
//    return new App($sm);
//}
//)()->dispatch(ServerRequestFactory::createFromGlobals($_SERVER));

$httpResponse = (new App(
    static function (Container $di): array {
        return $di->get('handlers');
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
))->dispatch(ServerRequestFactory::createFromGlobals($_SERVER));

