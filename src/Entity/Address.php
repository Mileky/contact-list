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
    private int $id;

    /**
     * Массив контактов адреса
     *
     * @var AbstractContact[]
     */
    private array $recipients;

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
     * @param int $id           -  ID адреса
     * @param array $recipients -  ID контакта
     * @param string $address   - Адрес контакта
     * @param string $status    - Статус адреса (работа/дом)
     */
    public function __construct(int $id, array $recipients, string $address, string $status)
    {
        $this->id = $id;
        $this->recipients = $recipients;
        $this->address = $address;
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return AbstractContact[]
     */
    public function getRecipients(): array
    {
        return $this->recipients;
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

    /**
     * @return string
     */
    public function getTitleContacts(): string
    {
        $titleContactId = [];
        foreach ($this->getRecipients() as $contact) {
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
