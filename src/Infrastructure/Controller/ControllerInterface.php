<?php

namespace DD\ContactList\Infrastructure\Controller;

use DD\ContactList\Infrastructure\Http\HttpResponse;
use DD\ContactList\Infrastructure\Http\ServerRequest;

/**
 * Интерфейс контроллеров
 */
interface ControllerInterface
{
    /**
     * Обработка http запроса
     *
     * @param ServerRequest $serverServerRequest
     *
     * @return HttpResponse
     */
    public function __invoke(ServerRequest $serverServerRequest): HttpResponse;

}