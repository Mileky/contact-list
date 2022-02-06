<?php

namespace DD\ContactList\Entity;

use DD\ContactList\ValueObject\Messenger;
use JsonSerializable;
use DD\ContactList\Exception;

class AbstractContact implements JsonSerializable
{
    /**
     * id Получателя
     *
     * @var int
     */
    private int $idRecipient;

    /**
     * Полное имя получателя
     *
     * @var string
     */
    private string $fullName;

    /**
     * Дата рождения получателя
     *
     * @var string
     */
    private string $birthday;

    /**
     * Профессия получателя
     *
     * @var string
     */
    private string $profession;

    /**
     * Данные о мессенджере, в котором есть пользователь
     *
     * @var Messenger[]
     */
    protected array $messengers;

    /**
     * @param int         $id_recipient
     * @param string      $full_name
     * @param string      $birthday
     * @param string      $profession
     * @param Messenger[] $messengers - Данные о мессенджере, в котором есть пользователь
     */
    public function __construct(
        int $id_recipient,
        string $full_name,
        string $birthday,
        string $profession,
        array $messengers
    ) {
        $this->idRecipient = $id_recipient;
        $this->fullName = $full_name;
        $this->birthday = $birthday;
        $this->profession = $profession;

        foreach ($messengers as $messenger) {
            if (!$messenger instanceof Messenger) {
                throw new Exception\DomainException('Некорректный формат данных о мессенджере');
            }
        }

        $this->messengers = $messengers;
    }

    /**
     * @return int
     */
    public function getIdRecipient(): int
    {
        return $this->idRecipient;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @return string
     */
    public function getBirthday(): string
    {
        return $this->birthday;
    }

    /**
     * @return string
     */
    public function getProfession(): string
    {
        return $this->profession;
    }

    /**
     * @return Messenger[]
     */
    public function getMessengers(): array
    {
        return $this->messengers;
    }


    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'id_recipient' => $this->idRecipient,
            'full_name'    => $this->fullName,
            'birthday'     => $this->birthday,
            'profession'   => $this->profession
        ];
    }
}
