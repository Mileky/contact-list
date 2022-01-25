<?php

namespace DD\ContactList\Infrastructure\Session;

use DD\ContactList\Infrastructure\Exception;

/**
 * Наттивная реализация работы с сессиями
 */
class SessionNative implements SessionInterface
{

    /**
     * Данные сессии
     *
     * @var array
     */
    private array $session;

    /**
     * @param array $session - Данные сессии
     */
    public function __construct(array &$session)
    {
        $this->session = &$session;
    }


    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->session);
    }

    /**
     * @inheritDoc
     */
    public function get(string $key)
    {
        if (false === $this->has($key)) {
            throw new Exception\RuntimeException('В сессии отсутствует значение для ключа ' . $key);
        }

        return $this->session[$key];
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, $value): SessionInterface
    {
        $this->session[$key] = $value;

        return $this;
    }

    /**
     * Создает сессию
     *
     * @return SessionNative
     */
    public static function create(): SessionNative
    {
        $sessionStatus = session_status();

        if (PHP_SESSION_DISABLED === $sessionStatus) {
            throw new Exception\RuntimeException('Сессии отключены');
        }

        if (PHP_SESSION_NONE === $sessionStatus) {
            session_start();
        }

        return new SessionNative($_SESSION);
    }
}