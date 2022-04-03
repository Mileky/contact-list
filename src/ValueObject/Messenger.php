<?php

namespace DD\ContactList\ValueObject;

use DD\ContactList\Exception;

class Messenger
{
    private const NAME_MESSENGERS = [
        'telegram',
        'viber',
        'whatsapp'
    ];

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
        $this->validateTypeMessenger($typeMessenger);
        $this->validateUsername($username);
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

    private function validateTypeMessenger($typeMessenger): void
    {
        if (!ctype_alpha($typeMessenger)) {
            throw new Exception\DomainException('Некорректное название мессенджера');
        }

        if (false === in_array($typeMessenger, self::NAME_MESSENGERS, true)) {
            throw new Exception\RuntimeException('Неверное имя мессенджера');
        }
    }

    private function validateUsername(string $username): void
    {
        if (40 < strlen($username)) {
            throw new Exception\RuntimeException('Некорректная длина имени пользователя');
        }
        if (1 !== preg_match('/^[a-zA-Z][a-zA-Z0-9-]+$/', $username)) {
            throw new Exception\RuntimeException('Некорректное имя пользователя');
        }
    }
}
