<?php

namespace DD\ContactList\Service\SearchAddressService;

class AddressDto
{
    /**
     * Id адреса
     *
     * @var int
     */
    private int $id_address;

    /**
     * Id контакта
     *
     * @var int
     */
    private int $id_recipient;

    /**
     * Адрес контакта
     *
     * @var string
     */
    private string $address;

    /**
     * Статус адреса (дом/работа)
     *
     * @var string
     */
    private string $status;

    /**
     * @param int    $id_address   - Id адреса
     * @param int    $id_recipient - Id контакта
     * @param string $address      - Адрес контакта
     * @param string $status       - Статус адреса (дом/работа)
     */
    public function __construct(int $id_address, int $id_recipient, string $address, string $status)
    {
        $this->id_address = $id_address;
        $this->id_recipient = $id_recipient;
        $this->address = $address;
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getIdAddress(): int
    {
        return $this->id_address;
    }

    /**
     * @return int
     */
    public function getIdRecipient(): int
    {
        return $this->id_recipient;
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