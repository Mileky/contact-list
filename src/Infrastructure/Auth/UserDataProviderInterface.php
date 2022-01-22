<?php

namespace DD\ContactList\Infrastructure\Auth;

/**
 * Провайдер данных о пользователе
 */
interface UserDataProviderInterface
{
    /**
     * Возвращает логин
     *
     * @return string
     */
    public function getLogin(): string;

    /**
     * Возвращает пароль
     *
     * @return string
     */
    public  function getPassword(): string;
}