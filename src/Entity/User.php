<?php

namespace DD\ContactList\Entity;

/**
 * Пользователь системы
 */
class User
{
    /**
     * ID юзера
     *
     * @var int
     */
    private int $id;

    /**
     * Логин юзера
     *
     * @var string
     */
    private string $login;

    /**
     * Пароль юзера
     *
     * @var string
     */
    private string $password;

    /**
     * @param int    $id       - ID юзера
     * @param string $login    - Логин юзера
     * @param string $password - Пароль юзера
     */
    public function __construct(int $id, string $login, string $password)
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * Возвращает ID юзера
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Возвращает Логин юзера
     *
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * Возвращает Пароль юзера
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
