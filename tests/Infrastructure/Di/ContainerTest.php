<?php

namespace DD\ContactListTest\Infrastructure\Di;

use DD\ContactList\Config\AppConfig;
use DD\ContactList\Controller\GetContactsCollectionController;
use DD\ContactList\Entity\ContactRepositoryInterface;
use DD\ContactList\Infrastructure\DataLoader\DataLoaderInterface;
use DD\ContactList\Infrastructure\DataLoader\JsonDataLoader;
use DD\ContactList\Infrastructure\DI\Container;
use DD\ContactList\Infrastructure\DI\ContainerInterface;
use DD\ContactList\Infrastructure\Logger\Adapter\NullAdapter;
use DD\ContactList\Infrastructure\Logger\AdapterInterface;
use DD\ContactList\Infrastructure\Logger\Logger;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;
use DD\ContactList\Repository\ContactJsonFileRepository;
use DD\ContactList\Service\SearchContactService;
use PHPUnit\Framework\TestCase;

/**
 * Тестирование получения сервиса
 */
class ContainerTest extends TestCase
{
    /**
     * Тестирование получения сервиса
     *
     * @return void
     */
    public function testGetService(): void
    {
        //Arrange
        $diConfig = [
            'instances' => [
                'appConfig' => require __DIR__ . '/../../../config/dev/config.php'
            ],
            'services' => [
                DataLoaderInterface::class => [
                    'class' => JsonDataLoader::class
                ],

                GetContactsCollectionController::class => [
                    'args' => [
                        'logger' => LoggerInterface::class,
                        'searchContactService' => SearchContactService::class
                    ]
                ],
                SearchContactService::class => [
                    'args' => [
                        'logger' => LoggerInterface::class,
                        'contactRepository' => ContactRepositoryInterface::class
                    ]
                ],
                ContactRepositoryInterface::class => [
                    'class' => ContactJsonFileRepository::class,
                    'args' => [
                        'dataLoader' => DataLoaderInterface::class,
                        'pathToRecipients' => 'pathToRecipients',
                        'pathToCustomers' => 'pathToCustomers',
                        'pathToKinsfolk' => 'pathToKinsfolk',
                        'pathToColleagues' => 'pathToColleagues'
                    ]
                ],
                LoggerInterface::class => [
                    'class' => Logger::class,
                    'args' => [
                        'adapter' => AdapterInterface::class
                    ]
                ],
                AdapterInterface::class => [
                    'class' => NullAdapter::class
                ],
            ],
            'factories' => [
                'pathToLogFile' => static function (ContainerInterface $c): string {
                    /** @var AppConfig $appConfig */
                    $appConfig = $c->get(AppConfig::class);
                    return $appConfig->getPathToLogFile();
                },
                AppConfig::class => static function (ContainerInterface $c) {
                    $appConfig = $c->get('appConfig');
                    return AppConfig::createFromArray($appConfig);
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
            ],
        ];

        $di = Container::createFromArray($diConfig);

        //Act
        $controller = $di->get(GetContactsCollectionController::class);

        //Assert
        $this->assertInstanceOf(
            GetContactsCollectionController::class,
            $controller,
            'Контейнер отработал некорректно'
        );
    }
}
