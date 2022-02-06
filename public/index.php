<?php

require_once __DIR__ . '/../vendor/autoload.php';


use DD\ContactList\Infrastructure\DI\ContainerInterface;
use DD\ContactList\Infrastructure\DI\SymfonyDiContainerInit;
use DD\ContactList\Infrastructure\HttpApplication\App;
use DD\ContactList\Config\AppConfig;
use DD\ContactList\Infrastructure\Http\ServerRequestFactory;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;
use DD\ContactList\Infrastructure\Router\RouterInterface;
use DD\ContactList\Infrastructure\View\RenderInterface;

$httpResponse = (new App(
    static function (ContainerInterface $di): RouterInterface {
        return $di->get(RouterInterface::class);
    },
    static function (ContainerInterface $di): LoggerInterface {
        return $di->get(LoggerInterface::class);
    },
    static function (ContainerInterface $di): AppConfig {
        return $di->get(AppConfig::class);
    },
    static function (ContainerInterface $di): RenderInterface {
        return $di->get(RenderInterface::class);
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
))->dispatch(ServerRequestFactory::createFromGlobals($_SERVER, file_get_contents('php://input')));

