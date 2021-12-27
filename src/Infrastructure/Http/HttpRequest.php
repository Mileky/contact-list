<?php

namespace DD\ContactList\Infrastructure\Http;

use DD\ContactList\Infrastructure\Uri\Uri;

/**
 * http запрос
 */
class HttpRequest extends AbstractMessage
{
    /**
     * http метод
     *
     * @var string
     */
    private string $method;

    /**
     * Цель запроса
     *
     * @var string
     */
    private string $requestTarget;

    /**
     * URI
     *
     * @var Uri
     */
    private Uri $uri;

    /**
     * @param string $method          - http метод
     * @param string $protocolVersion - Версия протокола
     * @param string $requestTarget   - Цель запроса
     * @param Uri $uri                - URI
     * @param array $headers          - Заголовки http сообщения
     * @param string|null $body       - Тело запроса
     */
    public function __construct(
        string $method,
        string $protocolVersion,
        string $requestTarget,
        Uri $uri,
        array $headers,
        ?string $body
    ) {
        parent::__construct($protocolVersion, $headers, $body);
        $this->method = $method;
        $this->requestTarget = $requestTarget;
        $this->uri = $uri;
    }

    /**
     * http метод
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Цель запроса
     *
     * @return string
     */
    public function getRequestTarget(): string
    {
        return $this->requestTarget;
    }

    /**
     * URI
     *
     * @return Uri
     */
    public function getUri(): Uri
    {
        return $this->uri;
    }

}