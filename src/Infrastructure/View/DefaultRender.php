<?php

namespace DD\ContactList\Infrastructure\View;

use DD\ContactList\Infrastructure\Http\HttpResponse;

/**
 * Логика отображения ответа пользователя по умолчанию
 */
class DefaultRender implements RenderInterface
{

    /**
     * @inheritDoc
     */
    public function render(HttpResponse $httpResponse): void
    {
        foreach ($httpResponse->getHeaders() as $headerName => $headerValue) {
            header("$headerName: $headerValue");
        }

        http_response_code($httpResponse->getStatusCode());
        echo $httpResponse->getBody();
    }
}