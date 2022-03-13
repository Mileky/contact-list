<?php

namespace DD\ContactList\Service\ArrivalNewAddressService;

use DD\ContactList\Entity\AbstractContact;

class NewAddressDto
{
    /**
     * Id контакта
     *
     * @var array
     */
    private array $idContact;

    /**
     * Адрес контакта
     *
     * @var string
     */
    private string $address;

    /**
     * Статус адреса (работа, дом)
     *
     * @var string
     */
    private string $status;

    /**
     * @param array    $idContact - Id контакта
     * @param string $address   - Адрес контакта
     * @param string $status    - Статус адреса (работа, дом)
     */
    public function __construct(array $idContact, string $address, string $status)
    {
        $this->idContact = $idContact;
        $this->address = $address;
        $this->status = $status;
    }

    /**
     * @return int[]
     */
    public function getIdContacts(): array
    {
        return $this->idContact;
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
