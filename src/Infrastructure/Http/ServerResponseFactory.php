<?php

namespace DD\ContactList\Infrastructure\Http;

use Throwable;
use DD\ContactList\Exception;

/**
 * Фабрика создания http ответов
 */
class ServerResponseFactory
{
    /**
     * Расшифровка http кодов
     */
    private const PHRASES = [
        200 => 'OK',
        201 => 'Created',
        404 => 'Not found',
        500 => 'Internal Server Error',
        503 => 'Service Unavailable',
        520 => 'Unknown error',

    ];

    /**
     * Создает http ответ с данными
     *
     * @param int $code
     * @param array $data
     *
     * @return HttpResponse
     */
    public static function createJsonResponse(int $code, array $data): HttpResponse
    {
        try {
            $body = json_encode($data, JSON_THROW_ON_ERROR);

            if (!array_key_exists($code, self::PHRASES)) {
                throw new Exception\RuntimeException("Некорректный код ответа: '$code'");
            }

            $phrases = self::PHRASES[$code];
        } catch (Throwable $e) {
            $body = '{"status": "fail", "message": "response config error"}';
            $code = 520;
            $phrases = 'Unknown error';
        }


        return new HttpResponse('1.1', $code, $phrases, ['Content-Type' => 'application/json'], $body);
    }

    /**
     * Создание http ответа с html данными
     *
     * @param int $code    - код ответа
     * @param string $html - строка html
     *
     * @return HttpResponse
     */
    public static function createHtmlResponse(int $code, string $html): HttpResponse
    {
        try {
            if (false === array_key_exists($code, self::PHRASES)) {
                throw new Exception\RuntimeException("Некорректный код ответа: '$code'");
            }

            $phrases = self::PHRASES[$code];
        } catch (Throwable $e) {
            $html = '<h1>Unknown Error</h1>';

            $code = 520;
            $phrases = self::PHRASES[$code];
        }

        return new HttpResponse('1.1', $code, $phrases, ['Content-Type' => 'text/html'], $html);
    }
}