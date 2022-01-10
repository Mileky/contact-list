<?php

namespace DD\ContactList\Infrastructure\Router;

use DD\ContactList\Infrastructure\Http\ServerRequest;

/**
 * Роутер сопоставляющий регулярные выражения и обработчик
 */
class RegExpRouter implements RouterInterface
{

    /**
     * @inheritDoc
     */
    public function getDispatcher(ServerRequest $serverRequest): ?callable
    {
        return null;
    }
}