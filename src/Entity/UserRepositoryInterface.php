<?php

namespace DD\ContactList\Entity;

interface UserRepositoryInterface
{
    /**
     * Поиск сущностей по заданному критерию
     *
     * @param array $criteria
     *
     * @return User[]
     */
    public function findBy(array $criteria): array;

    /**
     * Поиск пользователя по логину
     *
     * @param string $login - логин пользователя
     *
     * @return User|null - сущность пользователя
     */
    public function findUserByLogin(string $login): ?User;

}