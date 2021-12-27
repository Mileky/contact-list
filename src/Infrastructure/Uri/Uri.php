<?php

namespace DD\ContactList\Infrastructure\Uri;


/**
 * Uri
 */
final class Uri
{
    /**
     * http схема
     *
     * @var string
     */
    private string $scheme;

    /**
     * Хост
     *
     * @var string
     */
    private string $host;

    /**
     * Порт
     *
     * @var int|null
     */
    private ?int $port;

    /**
     * Путь к ресурсу
     *
     * @var string
     */
    private string $path;

    /**
     * Параметры запроса
     *
     * @var string
     */
    private string $query;

    /**
     * Якорь
     *
     * @var string
     */
    private string $fragment;

    /**
     * Инофрмация о пользователе (логин и пароль)
     *
     * @var string
     */
    private string $userInfo;

    /**
     * @param string $scheme   - http схема
     * @param string $userInfo - Инофрмация о пользователе (логин и пароль)
     * @param string $host     - Хост
     * @param int|null $port   - Порт
     * @param string $path     - Путь к ресурсу
     * @param string $query    - Параметры запроса
     * @param string $fragment - Якорь
     */
    public function __construct(
        string $scheme = '',
        string $userInfo = '',
        string $host = '',
        ?int $port = null,
        string $path = '',
        string $query = '',
        string $fragment = ''
    ) {
        $this->scheme = $scheme;
        $this->host = $host;
        $this->port = $port;
        $this->path = $path;
        $this->query = $query;
        $this->fragment = $fragment;
        $this->userInfo = $userInfo;
    }

    /**
     * http схема
     *
     * @return string|null
     */
    public function getScheme(): ?string
    {
        return $this->scheme;
    }

    /**
     * Хост
     *
     * @return string|null
     */
    public function getHost(): ?string
    {
        return $this->host;
    }

    /**
     * Порт
     *
     * @return int|null
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * Путь к ресурсу
     *
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * Параметры запроса
     *
     * @return string|null
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * Якорь
     *
     * @return string|null
     */
    public function getFragment(): ?string
    {
        return $this->fragment;
    }

    /**
     * Информация о пользователе (логин и пароль)
     *
     * @return string|null
     */
    public function getUserInfo(): ?string
    {
        return $this->userInfo;
    }

    public function __toString()
    {
        $scheme = '' === $this->scheme ? '' : "$this->scheme://";
        $userInfo = '' === $this->userInfo ? '' : "$this->userInfo@";
        $port = null === $this->port ? '' : ":$this->port";
        $query = '' === $this->query ? '' : "?$this->query";
        $fragment = '' === $this->fragment ? '' : "#$this->fragment";

        return "$scheme" . "$userInfo" . "$this->host" . "$port" . "$this->path" . "$query" . "$fragment";
    }

    /**
     * Создание объекта uri из строки
     *
     * @param string $uri
     *
     * @return Uri
     */
    public static function createFromString(string $uri): Uri
    {
        $urlParts = parse_url($uri);
        if (!is_array($urlParts)) {
            throw new Exception\ErrorUrlException("Ошибка разбора строки '$uri' на составные части");
        }

        $scheme = array_key_exists('scheme', $urlParts) ? $urlParts['scheme'] : '';
        $host = array_key_exists('host', $urlParts) ? $urlParts['host'] : '';
        $port = $urlParts['port'] ?? null;
        $userInfo = array_key_exists('user', $urlParts) ? $urlParts['user'] : '';
        if (array_key_exists('pass', $urlParts)) {
            $userInfo .= ":{$urlParts['pass']}";
        }
        $path = array_key_exists('path', $urlParts) ? $urlParts['path'] : '';
        $query = array_key_exists('query', $urlParts) ? $urlParts['query'] : '';
        $fragment = array_key_exists('fragment', $urlParts) ? $urlParts['fragment'] : '';


        return new Uri(
            $scheme,
            $userInfo,
            $host,
            $port,
            $path,
            $query,
            $fragment
        );
    }

}