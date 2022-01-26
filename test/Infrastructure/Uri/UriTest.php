<?php

namespace DD\ContactListTest\Infrastructure\Uri;

require_once __DIR__ . '/../../../vendor/autoload.php';

use DD\ContactList\Infrastructure\Uri\Uri;

/**
 * Тестирование URI
 */
final class UriTest
{
    /**
     * Тестирование преобразования URI В строку
     *
     * @return void
     */
    public static function testUriToString(): void
    {
        echo "-----------Тестирование преобразования URI В строку-----------\n";

        //Arrange
        $expected = "http://and:mypassword@htmlbook.ru:80/samhtml/ssylki/absolyutnye-i-otnositelnye-ssylki?query=value1#fragment-example";
        $uri = new Uri(
            'http',
            'and:mypassword',
            'htmlbook.ru',
            80,
            '/samhtml/ssylki/absolyutnye-i-otnositelnye-ssylki',
            'query=value1',
            'fragment-example'

        );

        //Act
        $actualUriString = (string)$uri;

        //Assert
        if ($expected === $actualUriString) {
            echo "          OK - объект uri корректно преобразован в строку\n";
        } else {
            echo "          Fail - объект uri некорректно преобразован в строку. Ожидалось $expected. Актуальное значение $actualUriString\n";
        }
    }

    /**
     * Тестирование создания объекта uri из строки
     *
     * @return void
     */
    public static function testCreateFromString(): void
    {
        echo "-----------Тестирование создания объекта URI из строки-----------\n";

        //Arrange
        $expected = "http://and:mypassword@htmlbook.ru:80/samhtml/ssylki/absolyutnye-i-otnositelnye-ssylki?query=value1#fragment-example";

        //Act
        $uri = Uri::createFromString($expected);
        $actualUriString = (string)$uri;

        //Assert
        if ($expected === $actualUriString) {
            echo "          OK - объект uri корректно создан из строки\n";
        } else {
            echo "          Fail - объект uri некорректно создан из строки. Ожидалось $expected. Актуальное значение $actualUriString\n";
        }
    }
}

UriTest::testUriToString();
UriTest::testCreateFromString();