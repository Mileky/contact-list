<?php

namespace DD\ContactList\Infrastructure\Auth;

/**
 * Хранилище данных о пользователе
 */
interface UserDataStorageInterface
{
    /**
     * Поиск пользователя по логину
     *
     * @param string $login
     *
     * @return UserDataProviderInterface|null
     */
    public function findUserByLogin(string $login): ?UserDataProviderInterface;
}