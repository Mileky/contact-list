<?php

namespace DD\ContactList\Infrastructure\Router;

use DD\ContactList\Infrastructure\Http\ServerRequest;

/**
 * Интерфейс роутеров
 */
interface RouterInterface
{
    /**
     * Возвращает обработчик запроса
     *
     * @param ServerRequest $serverRequest - объект серверного http-запроса
     *
     * @return callable|null
     */
    public function getDispatcher(ServerRequest $serverRequest): ?callable;

}