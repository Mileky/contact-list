<?php

namespace DD\ContactList\Infrastructure\Http;

/**
 * Серверный запрос
 */
class ServerRequest extends HttpRequest
{
    /**
     * Параметры запроса
     *
     * @var array|null
     */
    private ?array $queryParams = null;

    /**
     * Возвращает параметры запроса
     *
     * @return array
     */
    public function getQueryParams(): array
    {
        if (null === $this->queryParams) {
            $queryParams = [];
            parse_str($this->getUri()->getQuery(), $queryParams);
            $this->queryParams = $queryParams;
        }


        return $this->queryParams;
    }
}