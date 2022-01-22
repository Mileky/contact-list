<?php

namespace DD\ContactList\Infrastructure\Http;

use DD\ContactList\Exception\RuntimeException;
use DD\ContactList\Infrastructure\Uri\Uri;
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
        301 => 'Moved Permanently',
        302 => 'Found',
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

    /**
     * Редирект
     *
     * @param Uri $uri - URI на который должно произойти перенаправление в результате редиректа
     * @param int $httpCode - http код для редиректа. Код из подмножества 3хх
     *
     * @return HttpResponse - http ответ инициирующий редирект
     */
    public static function redirect(Uri $uri, int $httpCode = 302): HttpResponse
    {
        try {

            if (!($httpCode >= 300 && $httpCode < 400)) {
                throw new RuntimeException('Некорректный код ответа для редиректа');
            }

            if (false === array_key_exists($httpCode, self::PHRASES)) {
                throw new RuntimeException('Некорректный код ответа для редиректа');
            }

            $phrases = self::PHRASES[$httpCode];
            $body = '';
            $headers = ['Location' => (string)$uri];

        } catch (Throwable $exception) {
            $body = '<h1>Unknown Error</h1>';
            $httpCode = 520;
            $phrases = 'Unknown error';
            $headers = ['Content-Type' => 'text/html'];
        }

        return new HttpResponse('1.1', $httpCode, $phrases, $headers, $body);

    }

}