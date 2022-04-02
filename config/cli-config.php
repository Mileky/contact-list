<?php

use DD\ContactList\Config\ContainerExtensions;
use DD\ContactList\Infrastructure\DI\SymfonyDiContainerInit;
use DD\ContactList\Infrastructure\DI\SymfonyDiContainerInit\CacheParams;
use DD\ContactList\Infrastructure\DI\SymfonyDiContainerInit\ContainerParams;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once __DIR__ . '/../vendor/autoload.php';

$container = (new SymfonyDiContainerInit(
    new ContainerParams(
        __DIR__ . '/../config/dev/di.xml',
        ['kernel.project_dir' => __DIR__ . '/../'],
        ContainerExtensions::httpAppContainerExtension()
    ),
    new CacheParams(
        getenv('ENV_TYPE') !== 'DEV',
        __DIR__ . '/../var/cache/di-symfony/EfTechBookLibraryCachedContainer.php'
    )
))();

$entityManager = $container->get(EntityManagerInterface::class);

return ConsoleRunner::createHelperSet($entityManager);