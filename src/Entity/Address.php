<?php

namespace DD\ContactList\Entity;

use DD\ContactList\Exception\InvalidDataStructureException;
use DD\ContactList\Exception\RuntimeException;

/**
 * Класс, описывающий сущность Адрес
 */
class Address
{
    /**
     * ID адреса
     *
     * @var int
     */
    private int $idAddress;

    /**
     * Массив контактов адреса
     *
     * @var AbstractContact[]
     */
    private array $idRecipient;

    /**
     * Адрес контакта
     *
     * @var string
     */
    private string $address;

    /**
     * Статус адреса (работа/дом)
     *
     * @var string
     */
    private string $status;

    /**
     * @param int $idAddress     -  ID адреса
     * @param array $idRecipient -  ID контакта
     * @param string $address    - Адрес контакта
     * @param string $status     - Статус адреса (работа/дом)
     */
    public function __construct(int $idAddress, array $idRecipient, string $address, string $status)
    {
        $this->idAddress = $idAddress;
        $this->idRecipient = $idRecipient;
        $this->address = $address;
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getIdAddress(): int
    {
        return $this->idAddress;
    }

    /**
     * @return AbstractContact[]
     */
    public function getRecipient(): array
    {
        return $this->idRecipient;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    public function jsonSerialize(): array
    {
        $jsonData['id'] = $this->idAddress;
        $jsonData['id_recipient'] = $this->idRecipient;
        $jsonData['address_data'] = $this->address;
        $jsonData['status'] = $this->status;
        return $jsonData;
    }

    /**
     * @return string
     */
    public function getTitleContacts(): string
    {
        $titleContactId = [];
        foreach ($this->getRecipient() as $contact) {
            $titleContactId[] = $contact->getIdRecipient();
        }
        return implode(', ', $titleContactId);
    }

    public static function createFromArray(array $data): Address
    {
        $requiredFields = [
            'id',
            'id_recipient',
            'address_data',
            'status'
        ];

        $missingFields = array_diff($requiredFields, array_keys($data));

        if (count($missingFields) > 0) {
            $errMsg = sprintf('Отсутствуют обязательные элементы: %s', implode(',', $missingFields));
            throw new InvalidDataStructureException($errMsg);
        }

        return new Address(
            $data['id'],
            $data['id_recipient'],
            $data['address_data'],
            $data['status']
        );
    }
}
