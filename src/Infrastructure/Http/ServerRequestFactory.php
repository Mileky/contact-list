<?php

namespace DD\ContactList\Infrastructure\Http;

use DD\ContactList\Infrastructure\Http\Exception\ErrorHttpRequestException;
use DD\ContactList\Infrastructure\Uri\Uri;

/**
 * Фабрика отвечающая за создание объекта ServerRequest
 */
class ServerRequestFactory
{
    private const ALLOWED_HTTP_METHOD = [
        'GET',
        'POST',
        'PUT',
        'DELETE'
    ];

    /**
     * Обязательные ключи в массиве входных данных
     */
    private const REQUIRED_FIELDS = [
        'SERVER_PROTOCOL',
        'SERVER_PORT',
        'REQUEST_URI',
        'REQUEST_METHOD',
        'SERVER_NAME',
    ];

    /**
     * Валидация наличия обязательных полей. Проверка заданных полей на соответствие заданному типу данных
     *
     * @param array $globalServer - валидируемые данные из $_SERVER
     *
     * @return void
     */
    private static function validateRequiredFields(array $globalServer): void
    {
        foreach (self::REQUIRED_FIELDS as $fieldName) {
            if (!array_key_exists($fieldName, $globalServer)) {
                throw new ErrorHttpRequestException(
                    "Для создания объекта серверного http запроса необходимо знать: '$fieldName'"
                );
            }
            if (!is_string($globalServer[$fieldName])) {
                throw new ErrorHttpRequestException(
                    "Для создания объекта серверного http запроса необхходимо чтобы '$fieldName' было представлено строкой"
                );
            }
        }
    }

    /**
     * Проверяет на валидный http метод
     *
     * @param string $httpMethod
     *
     * @return void
     */
    private static function httpValidateMethod(string $httpMethod): void
    {
        if (!in_array($httpMethod, self::ALLOWED_HTTP_METHOD)) {
            throw new ErrorHttpRequestException("Некорректный http метод: $httpMethod");
        }
    }

    /**
     * Извлечение версии протокола
     *
     * @param string $protocolVersionRaw
     *
     * @return string
     */
    private static function extractProtocolVersion(string $protocolVersionRaw): string
    {
        if ('HTTP/1.1' === $protocolVersionRaw) {
            $version = '1.1';
        } elseif ('HTTP/1.0' === $protocolVersionRaw) {
            $version = '1.0';
        } else {
            throw new ErrorHttpRequestException("Неподдерживающаяся версия HTTP протокола: '$protocolVersionRaw'");
        }

        return $version;
    }

    /**
     * Извлечение заголовков
     *
     * @param array $globalServer
     *
     * @return array
     */
    private static function extractHeaders(array $globalServer): array
    {
        $headers = [];

        foreach ($globalServer as $key => $value) {
            if (0 === strpos($key, 'HTTP_')) {
                $name = str_replace('_', '-', strtolower(substr($key, 5)));
                $headers[$name] = $value;
            }
        }

        return $headers;
    }

    /**
     * Собирает uri из $_SERVER
     *
     * @param array $globalServer
     *
     * @return string
     */
    private static function buildUri(array $globalServer): string
    {
        $uri = $globalServer['REQUEST_URI'];
        if ('' !== $globalServer['SERVER_NAME']) {
            $uriServerInfo = self::extractUriScheme($globalServer) . '://' . $globalServer['SERVER_NAME'];
            self::validatePort($globalServer['SERVER_PORT']);
            $uriServerInfo .= ':' . $globalServer['SERVER_PORT'];

            if (0 === strpos($uri, '/')) {
                $uri = $uriServerInfo . $uri;
            } else {
                $uri = $uriServerInfo . '/' . $uri;
            }
        }

        return $uri;
    }

    /**
     * Извлечение схемы
     *
     * @param array $globalServer
     *
     * @return string
     */
    private static function extractUriScheme(array $globalServer): string
    {
        $scheme = 'http';
        if (array_key_exists('HTTPS', $globalServer) && 'on' === $globalServer['HTTPS']) {
            $scheme = 'https';
        }

        return $scheme;
    }

    /**
     * Валидация порта
     *
     * @param string $portString
     *
     * @return void
     */
    private static function validatePort(string $portString): void
    {
        if ($portString !== (string)((int)$portString)) {
            throw new ErrorHttpRequestException("Неподдерживающийся номер порта: '$portString'");
        }
    }


    /**
     * Создает серверный объект запроса из гнлобальных переменных
     *
     * @param array $globalServer - данные из глобального массива $_SERVER
     * @param string|null $body   - тело http запроса
     *
     * @return ServerRequest
     */
    public static function createFromGlobals(array $globalServer, string $body = null): ServerRequest
    {
        self::validateRequiredFields($globalServer);
        self::httpValidateMethod($globalServer['REQUEST_METHOD']);

        $method = $globalServer['REQUEST_METHOD'];
        $requestTarget = $globalServer['REQUEST_URI'];
        $protocolVersion = self::extractProtocolVersion($globalServer['SERVER_PROTOCOL']);

        $uri = Uri::createFromString(self::buildUri($globalServer));
        $headers = self::extractHeaders($globalServer);

        return new ServerRequest($method, $protocolVersion, $requestTarget, $uri, $headers, $body);
    }
}