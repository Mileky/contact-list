<?php

namespace DD\ContactList\Infrastructure\HttpApplication;

/**
 * Конфиг движка сайта
 */
interface AppConfigInterface
{
    /**
     * Возвращает флаг, указывающий, что нужно скрывать сообщения об ошибках
     *
     * @return bool
     */
    public function isHideErrorMessage(): bool;
}