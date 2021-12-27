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
        404 => 'Not found',
        500 => 'Internal Server Error',
        503 => 'Service Unavailable',

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
}