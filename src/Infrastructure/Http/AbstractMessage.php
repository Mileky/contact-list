<?php

namespace DD\ContactList\Infrastructure\Http;

/**
 * Абстракция http сообщения
 */
abstract class AbstractMessage
{
    /**
     * Версия протокола
     *
     * @var string
     */
    private string $protocolVersion;

    /**
     * Заголовки http сообщения
     *
     * @var array
     */
    private array $headers;

    /**
     * Тело запроса
     *
     * @var string|null
     */
    private ?string $body;

    /**
     * @param string $protocolVersion - Версия протокола
     * @param array $headers          - Заголовки http сообщения
     * @param string|null $body       - Тело запроса
     */
    public function __construct(string $protocolVersion, array $headers, ?string $body)
    {
        $this->protocolVersion = $protocolVersion;
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * Версия протокола
     *
     * @return string
     */
    final public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * Заголовки http сообщения
     *
     * @return array
     */
    final public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Тело запроса
     *
     * @return string|null
     */
    final public function getBody(): ?string
    {
        return $this->body;
    }


}