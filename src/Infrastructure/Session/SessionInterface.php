<?php

namespace DD\ContactList\Infrastructure\Session;

/**
 * Интерфейс для работы с сессиями
 */
interface SessionInterface
{
    /**
     * Проверяет есть ли в сессии данные о заданному ключу
     *
     * @param string $key - ключ в сессии
     *
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Возвращает данные из сессии по заданному ключу
     *
     * @param string $key - ключ в сессии
     *
     * @return mixed
     */
    public function get(string $key);

    /**
     * Устанавливает данные в сессии
     *
     * @param string $key - ключ в сессии
     * @param $value      - значение сохраняемое в сессию
     *
     * @return $this
     */
    public function set(string $key, $value): self;
}