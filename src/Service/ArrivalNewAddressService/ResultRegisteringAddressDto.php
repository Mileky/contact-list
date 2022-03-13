<?php

namespace DD\ContactList\Service\ArrivalNewAddressService;

use DD\ContactList\Entity\AbstractContact;

class ResultRegisteringAddressDto
{
    /**
     * Id адреса
     *
     * @var int
     */
    private int $idAddress;

    /**
     * Id контакта
     *
     * @var array
     */
    private array $contacts;

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
     * @param int    $idAddress - Id адреса
     * @param array    $contacts - Id контакта
     * @param string $address   - Адрес контакта
     * @param string $status    - Статус адреса (работа/дом)
     */
    public function __construct(int $idAddress, array $contacts, string $address, string $status)
    {
        $this->idAddress = $idAddress;
        $this->contacts = $contacts;
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
     * @return array
     */
    public function getContacts(): array
    {
        return $this->contacts;
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
}
