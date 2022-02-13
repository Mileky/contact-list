<?php

namespace DD\ContactListTest;

use DD\ContactList\Config\ContainerExtensions;
use DD\ContactList\Infrastructure\DI\SymfonyDiContainerInit;
use DD\ContactList\Infrastructure\Di\SymfonyDiContainerInit\ContainerParams;
use Exception;
use PHPUnit\Framework\TestCase;
use DD\ContactList;

class DiAppServiceTest extends TestCase
{

    /**
     * Провайдер данных для тестирования создаваемых сервисов
     *
     * @return array
     */
    public static function serviceDataProvider(): array
    {
        return [
            ContactList\Controller\AddressAdministrationController::class => [
                'serviceId' => ContactList\Controller\AddressAdministrationController::class,
                'expectedValue' => ContactList\Controller\AddressAdministrationController::class
            ],
            ContactList\Controller\LoginController::class => [
                'serviceId' => ContactList\Controller\LoginController::class,
                'expectedValue' => ContactList\Controller\LoginController::class
            ],
            ContactList\Infrastructure\Logger\LoggerInterface::class => [
                'serviceId' => ContactList\Infrastructure\Logger\LoggerInterface::class,
                'expectedValue' => ContactList\Infrastructure\Logger\Logger::class
            ],
            ContactList\Config\AppConfig::class => [
                'serviceId' => ContactList\Config\AppConfig::class,
                'expectedValue' => ContactList\Config\AppConfig::class
            ],
            ContactList\Controller\GetContactsCollectionController::class => [
                'serviceId' => ContactList\Controller\GetContactsCollectionController::class,
                'expectedValue' => ContactList\Controller\GetContactsCollectionController::class
            ],
            ContactList\Controller\GetContactsController::class => [
                'serviceId' => ContactList\Controller\GetContactsController::class,
                'expectedValue' => ContactList\Controller\GetContactsController::class
            ],
            ContactList\Controller\UpdateContactListController::class => [
                'serviceId' => ContactList\Controller\UpdateContactListController::class,
                'expectedValue' => ContactList\Controller\UpdateContactListController::class
            ],
            ContactList\Controller\CreateAddressController::class => [
                'serviceId' => ContactList\Controller\CreateAddressController::class,
                'expectedValue' => ContactList\Controller\CreateAddressController::class
            ],
            ContactList\ConsoleCommand\HashStr::class => [
                'serviceId' => ContactList\ConsoleCommand\HashStr::class,
                'expectedValue' => ContactList\ConsoleCommand\HashStr::class
            ],
            ContactList\ConsoleCommand\FindContacts::class => [
                'serviceId' => ContactList\ConsoleCommand\FindContacts::class,
                'expectedValue' => ContactList\ConsoleCommand\FindContacts::class
            ],
            ContactList\Infrastructure\View\RenderInterface::class => [
                'serviceId' => ContactList\Infrastructure\View\RenderInterface::class,
                'expectedValue' => ContactList\Infrastructure\View\DefaultRender::class
            ],
            ContactList\Infrastructure\Router\DefaultRouter::class => [
                'serviceId' => ContactList\Infrastructure\Router\DefaultRouter::class,
                'expectedValue' => ContactList\Infrastructure\Router\DefaultRouter::class
            ],
            ContactList\Infrastructure\Router\RegExpRouter::class => [
                'serviceId' => ContactList\Infrastructure\Router\RegExpRouter::class,
                'expectedValue' => ContactList\Infrastructure\Router\RegExpRouter::class
            ],
            ContactList\Infrastructure\Router\UniversalRouter::class => [
                'serviceId' => ContactList\Infrastructure\Router\UniversalRouter::class,
                'expectedValue' => ContactList\Infrastructure\Router\UniversalRouter::class
            ],
            ContactList\Infrastructure\Router\RouterInterface::class => [
                'serviceId' => ContactList\Infrastructure\Router\RouterInterface::class,
                'expectedValue' => ContactList\Infrastructure\Router\ChainRouters::class
            ],
        ];
    }

    /**
     * Проверяет корректность создания сервиса через di контейнер symfony
     *
     * @dataProvider serviceDataProvider
     * @runInSeparateProcess
     *
     * @param string $serviceId
     * @param string $expectedServiceClass
     *
     * @return void
     * @throws Exception
     */
    public function testCreateService(string $serviceId, string $expectedServiceClass): void
    {
        //Arrange
        $diContainerFactory = new SymfonyDiContainerInit(
            new ContainerParams(
                __DIR__ . '/../config/dev/di.xml',
                [
                    'kernel.project_dir' => __DIR__ . '/../'
                ],
                ContainerExtensions::httpAppContainerExtension()
            )
        );
        $diContainer = $diContainerFactory();

        //Act
        $actualService = $diContainer->get($serviceId);

        //Assert
        $this->assertInstanceOf($expectedServiceClass, $actualService);
    }
}
