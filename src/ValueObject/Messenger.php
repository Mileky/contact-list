<?php

namespace DD\ContactList\ValueObject;

use DD\ContactList\Exception;

final class Messenger
{
    /**
     * Мессенджер
     *
     * @var string
     */
    private string $typeMessenger;

    /**
     * Имя пользователя в мессенджере
     *
     * @var string
     */
    private string $username;

    /**
     * @param string $typeMessenger - Название мессенджера
     * @param string $username      - Имя пользователя в мессенджере
     */
    public function __construct(string $typeMessenger, string $username)
    {
        if (!ctype_alpha($typeMessenger)) {
            throw new Exception\DomainException('Некорректное название мессенджера');
        }
        $this->typeMessenger = $typeMessenger;
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getTypeMessenger(): string
    {
        return $this->typeMessenger;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }


}