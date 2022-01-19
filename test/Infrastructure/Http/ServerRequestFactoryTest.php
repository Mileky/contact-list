<?php

namespace DD\ContactListTest\Infrastructure\Http;

require_once __DIR__ . '/../../../src/Infrastructure/Autoloader.php';

use DD\ContactList\Infrastructure\Autoloader;
use DD\ContactList\Infrastructure\Http\ServerRequestFactory;
use DD\ContactListTest\UtilsTest;
use JsonException;

spl_autoload_register(
    new Autoloader([
        '\\DD\\ContactList\\' => __DIR__ . '/../../../src/',
        '\\DD\\ContactListTest\\' => __DIR__ . '/../../../test/',
    ])
);

/**
 * Тестирование логики работы фабрики, создающей серверный http запрос
 */
final class ServerRequestFactoryTest
{
    /**
     * Тестирование создания серверного запроса
     *
     * @return void
     * @throws JsonException
     */
    public static function createFromGlobals(): void
    {
        echo "-----------Тестирование создания серверного запроса-----------\n";

        //Arrange
        $servers = [
            'SERVER_PROTOCOL' => 'HTTP/1.1',
            'SERVER_PORT' => '80',
            'REQUEST_URI' => '/samhtml/ssylki/absolyutnye-i-otnositelnye-ssylki?query=value1#fragment-example',
            'REQUEST_METHOD' => 'GET',
            'SERVER_NAME' => 'localhost',

            'HTTP_HOST' => 'localhost:80',
            'HTTP_CONNECTION' => 'Keep-Alive',
            'HTTP_USER_AGENT' => 'Apache-HttpClient\/4.5.13 (Java\/11.0.11)',
            'HTTP_COOKIE' => 'XDEBUG_SESSION=16151',
        ];
        $expectedBody = 'test';
        $expectedProtocolVersion = '1.1';
        $method = 'GET';
        $requestTarget = '/samhtml/ssylki/absolyutnye-i-otnositelnye-ssylki?query=value1#fragment-example';

        //Act
        $httpServerRequest = ServerRequestFactory::createFromGlobals($servers, $expectedBody);
        $actualUriString = (string)$httpServerRequest->getUri();

        //Assert
        $expectedUri = 'http://localhost:80/samhtml/ssylki/absolyutnye-i-otnositelnye-ssylki?query=value1#fragment-example';
        if ($expectedUri === $actualUriString) {
            echo "          OK - объект серверного http запроса корректно создан\n";
        } else {
            echo "          Fail - объект серверного http запроса некорректно создан. Ожидалось $expectedUri. Актуальное значение $actualUriString\n";
        }

        if ($expectedBody === $httpServerRequest->getBody()) {
            echo "          OK - корректное тело запроса\n";
        } else {
            echo "          Fail - некорректное тело запроса. Ожидалось $expectedBody. Актуальное значение {$httpServerRequest->getBody()}\n";
        }

        if ($expectedProtocolVersion === $httpServerRequest->getProtocolVersion()) {
            echo "          OK - корректная версия http протокола\n";
        } else {
            echo "          Fail - некорректная версия http протокола. Ожидалось $expectedProtocolVersion. Актуальное значение {$httpServerRequest->getProtocolVersion()}\n";
        }

        if ($method === $httpServerRequest->getMethod()) {
            echo "          OK - корректный тип http метода\n";
        } else {
            echo "          Fail - некорректный тип http метода. Ожидалось $method. Актуальное значение {$httpServerRequest->getMethod()}\n";
        }

        if ($requestTarget === $httpServerRequest->getRequestTarget()) {
            echo "          OK - корректная цель http запроса\n";
        } else {
            echo "          Fail - некорректная цель http запроса. Ожидалось $requestTarget. Актуальное значение {$httpServerRequest->getRequestTarget()}\n";
        }

        $actualQueryParams = $httpServerRequest->getQueryParams();
        $expectedQueryParams = [
            'query' => 'value1'
        ];

        //Лишние элементы
        $unnecessaryQueryParams = UtilsTest::arrayDiffAssocRecursive($actualQueryParams, $expectedQueryParams);
        //Недостающие элементы
        $missingQueryParams = UtilsTest::arrayDiffAssocRecursive($expectedQueryParams, $actualQueryParams);

        $errMsg = '';

        if (count($unnecessaryQueryParams) > 0) {
            $errMsg .= sprintf(
                "         Есть лишние элементы %s\n",
                json_encode($unnecessaryQueryParams, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)
            );
        }
        if (count($missingQueryParams) > 0) {
            $errMsg .= sprintf(
                "         Есть лишние недостающие элементы %s\n",
                json_encode($missingQueryParams, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)
            );
        }

        if ('' === $errMsg) {
            echo "          ОК - данные параметров запроса валидны\n";
        } else {
            echo "          FAIL - данные параметров запроса невалидны\n" . $errMsg;
        }
    }
}

ServerRequestFactoryTest::createFromGlobals();