<?php

namespace DD\ContactListTest;

use DD\ContactList\Config\AppConfig;
use DD\ContactList\Infrastructure\DI\SymfonyDiContainerInit;
use Exception;
use PHPUnit\Framework\TestCase;

class DiAppConfigServiceTest extends TestCase
{
    /**
     * Поставщик данных для теста, проверяющего получение значений из конфига
     *
     * @return array
     */
    public static function appConfigDataProvider(): array
    {
        return [
            'pathToLogFile' => [
                'method' => 'getPathToLogFile',
                'expectedValue' => __DIR__ . '/../var/log/app.log'
            ],
            'pathToKinsfolk' => [
                'method' => 'getPathToKinsfolk',
                'expectedValue' => __DIR__ . '/../data/kinsfolk.json'
            ],
            'pathToColleagues' => [
                'method' => 'getPathToColleagues',
                'expectedValue' => __DIR__ . '/../data/colleagues.json'
            ],
            'pathToCustomers' => [
                'method' => 'getPathToCustomers',
                'expectedValue' => __DIR__ . '/../data/customers.json'
            ],
            'pathToRecipients' => [
                'method' => 'getPathToRecipients',
                'expectedValue' => __DIR__ . '/../data/recipient.json'
            ],
            'pathToAddress' => [
                'method' => 'getPathToAddress',
                'expectedValue' => __DIR__ . '/../data/address.json'
            ],
            'pathToContactList' => [
                'method' => 'getPathToContactList',
                'expectedValue' => __DIR__ . '/../data/contact_list.json'
            ],
            'pathToUsers' => [
                'method' => 'getPathToUsers',
                'expectedValue' => __DIR__ . '/../data/users.json'
            ],
            'hideErrorMessage' => [
                'method' => 'isHideErrorMessage',
                'expectedValue' => false,
                'isPath' => false
            ],
            'loginUri' => [
                'method' => 'getLoginUri',
                'expectedValue' => '/login',
                'isPath' => false
            ],
        ];
    }


    /**
     * Тестирование получения значений из конфига приложений
     *
     * @dataProvider appConfigDataProvider
     *
     * @param string $method
     * @param $expectedValue
     * @param bool $isPath
     *
     * @return void
     * @throws Exception
     */
    public function testAppConfigGetter(string $method, $expectedValue, bool $isPath = true): void
    {
        //Arrange
        $diContainerFactory = new SymfonyDiContainerInit(
            __DIR__ . '/../config/dev/di.xml',
            ['kernel.project_dir' => __DIR__ . '/../']
        );
        $diContainer = $diContainerFactory();
        $appConfig = $diContainer->get(AppConfig::class);

        //Act
        $actualValue = $appConfig->$method();

        //Assert
        if ($isPath) {
            $expectedValue = realpath($expectedValue);
            $actualValue = realpath($actualValue);
        }
        $this->assertSame($expectedValue, $actualValue);
    }
}
