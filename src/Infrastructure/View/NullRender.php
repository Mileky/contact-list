<?php

namespace DD\ContactList\Infrastructure\View;

use DD\ContactList\Infrastructure\Http\HttpResponse;

/**
 * Рендер в "никуда"
 */
class NullRender implements RenderInterface
{

    /**
     * @inheritDoc
     */
    public function render(HttpResponse $httpResponse): void
    {
    }
}