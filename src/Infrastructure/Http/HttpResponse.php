<?php

namespace DD\ContactList\Infrastructure\Http;

/**
 * http ответ
 */
class HttpResponse extends AbstractMessage
{
    /**
     * http код
     *
     * @var int
     */
    private int $statusCode;

    /**
     * Пояснение к http коду
     *
     * @var string
     */
    private string $reasonPhrase;

    /**
     * @param string $protocolVersion - версия протокола
     * @param int $statusCode         - http код
     * @param string $reasonPhrase    - пояснение к http коду
     * @param array $headers          - заголовки ответа
     * @param string|null $body       - тело ответа
     */
    public function __construct(
        string $protocolVersion,
        int $statusCode,
        string $reasonPhrase,
        array $headers,
        ?string $body
    ) {
        parent::__construct($protocolVersion, $headers, $body);
        $this->statusCode = $statusCode;
        $this->reasonPhrase = $reasonPhrase;
    }

    /**
     * http код
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * пояснение к http коду
     *
     * @return string
     */
    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }


}