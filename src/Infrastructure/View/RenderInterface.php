<?php

namespace DD\ContactList\Infrastructure\View;

use DD\ContactList\Infrastructure\Http\HttpResponse;

interface RenderInterface
{
    /**
     * Отображает результаты пользователя
     *
     * @param HttpResponse $httpResponse
     *
     * @return void
     */
    public function render(HttpResponse $httpResponse): void;

}