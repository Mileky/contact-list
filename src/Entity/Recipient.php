<?php

namespace DD\ContactList\Entity;

use DD\ContactList\Exception;
use JsonSerializable;

/**
 * Получатель
 */
class Recipient implements JsonSerializable
{
    /**
     * id Получателя
     *
     * @var int
     */
    private int $id_recipient;

    /**
     * Полное имя получателя
     *
     * @var string
     */
    private string $full_name;

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

    /** Конструктор класса
     *
     * @param int $id_recipient  - id Получателя
     * @param string $full_name  - Полное имя получателя
     * @param string $birthday   - Дата рождения получателя
     * @param string $profession - Профессия получателя
     */
    public function __construct(int $id_recipient, string $full_name, string $birthday, string $profession)
    {
        $this->id_recipient = $id_recipient;
        $this->full_name = $full_name;
        $this->birthday = $birthday;
        $this->profession = $profession;
    }


    /**
     * Возвращает id получателя
     *
     * @return int
     */
    final public function getIdRecipient(): int
    {
        return $this->id_recipient;
    }

    /**
     * Устанавливает id получателя
     *
     * @param int $id_recipient
     *
     * @return Recipient
     */
    public function setIdRecipient(int $id_recipient): Recipient
    {
        $this->id_recipient = $id_recipient;
        return $this;
    }

    /**
     * Возвращает полное имя получателя
     *
     * @return string
     */
    final public function getFullName(): string
    {
        return $this->full_name;
    }

    /**
     * Устанавливает полное имя получателя
     *
     * @param string $full_name
     *
     * @return Recipient
     */
    public function setFullName(string $full_name): Recipient
    {
        $this->full_name = $full_name;
        return $this;
    }

    /**
     * Возвращает дату рождения получателя
     *
     * @return string
     */
    final public function getBirthday(): string
    {
        return $this->birthday;
    }

    /**
     * Устанавливает дату рождения получателя
     *
     * @param string $birthday
     *
     * @return Recipient
     */
    public function setBirthday(string $birthday): Recipient
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * Возвращает профессию получателя
     *
     * @return string
     */
    final public function getProfession(): string
    {
        return $this->profession;
    }

    /**
     * Устанавливает профессию получателя
     *
     * @param string $profession
     *
     * @return Recipient
     */
    public function setProfession(string $profession): Recipient
    {
        $this->profession = $profession;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id_recipient' => $this->id_recipient,
            'full_name' => $this->full_name,
            'birthday' => $this->birthday,
            'profession' => $this->profession
        ];
    }

    /**
     * Создание сущности "Получатель" из массива
     *
     * @param array $data
     *
     * @return Recipient
     */
    public static function createFromArray(array $data): Recipient
    {
        $requiredFields = [
            'id_recipient',
            'full_name',
            'birthday',
            'profession'
        ];

        $missingFields = array_diff($requiredFields, array_keys($data));

        if (count($missingFields) > 0) {
            $errMsg = sprintf('Отсутствуют обязательные элементы: %s', implode(',', $missingFields));
            throw new Exception\InvalidDataStructureException($errMsg);
        }

        return new Recipient($data['id_recipient'], $data['full_name'], $data['birthday'], $data['profession']);
    }

}