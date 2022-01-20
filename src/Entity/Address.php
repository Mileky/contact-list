<?php

namespace DD\ContactList\Entity;

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
     * ID контакта
     *
     * @var AbstractContact
     */
    private AbstractContact $idRecipient;

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
     * @param int             $idAddress   -  ID адреса
     * @param AbstractContact $idRecipient -  ID контакта
     * @param string          $address     - Адрес контакта
     * @param string          $status      - Статус адреса (работа/дом)
     */
    public function __construct(int $idAddress, AbstractContact $idRecipient, string $address, string $status)
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
     * @return AbstractContact
     */
    public function getIdRecipient(): AbstractContact
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
        $jsonData['id_address'] = $this->idAddress;
        $jsonData['id_recipient'] = $this->idRecipient;
        $jsonData['address'] = $this->address;
        $jsonData['status'] = $this->status;
        return $jsonData;
    }
}