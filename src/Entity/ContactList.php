<?php

namespace DD\ContactList\Entity;

use DD\ContactList\Exception;

/**
 * Класс описывающий Список контактов
 */
class ContactList
{
    /**
     * ID записи
     *
     * @var int
     */
    private int $idEntry;

    /**
     * ID контакта
     *
     * @var int
     */
    private int $idRecipient;

    /**
     * Наличие в черном списке
     *
     * @var bool
     */
    private bool $blacklist;

    /**
     * @param int $idEntry     - ID записи
     * @param int $idRecipient - ID контакта
     * @param bool $blacklist  - Наличие в черном списке
     */
    public function __construct(int $idEntry, int $idRecipient, bool $blacklist)
    {
        $this->idEntry = $idEntry;
        $this->idRecipient = $idRecipient;
        $this->blacklist = $blacklist;
    }

    /**
     * @return int
     */
    public function getIdEntry(): int
    {
        return $this->idEntry;
    }

    /**
     * @return AbstractContact
     */
    public function getIdRecipient(): int
    {
        return $this->idRecipient;
    }

    /**
     * @return bool
     */
    public function isBlacklist(): bool
    {
        return $this->blacklist;
    }

    /**
     * Сериализация данных в массив для json
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        $jsonData['id_entry'] = $this->idEntry;
        $jsonData['id_recipient'] = $this->idRecipient;
        $jsonData['blacklist'] = $this->blacklist;

        return $jsonData;
    }

    public function moveToIgnore(): self
    {
        if (true === $this->blacklist) {
            throw new Exception\RuntimeException("Текстовый документ с id {$this->getIdRecipient()} уже находится в архиве");
        }

        $this->blacklist = true;

        return $this;
    }

    /**
     * Создание списка контактов
     *
     * @param array $data
     *
     * @return ContactList
     */
    public static function createFromArray(array $data): ContactList
    {
        $requiredFields = [
            'id_entry',
            'id_recipient',
            'blacklist'
        ];

        $missingFields = array_diff($requiredFields, array_keys($data));

        if (count($missingFields) > 0) {
            $errMsg = sprintf('Отсутствуют обязательные элементы: %s', implode(',', $missingFields));
            throw new Exception\InvalidDataStructureException($errMsg);
        }

        return new ContactList(
            $data['id_entry'],
            $data['id_recipient'],
            $data['blacklist']
        );
    }


}