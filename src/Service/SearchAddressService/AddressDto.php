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
     * @var array
     */
    private array $id_recipient;

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
     * Id контактов для печати на веб странице
     *
     * @var string
     */
    private string $titleContacts;

    /**
     * @param int $id_address - Id адреса
     * @param array $id_recipient - Id контакта
     * @param string $address - Адрес контакта
     * @param string $status - Статус адреса (дом/работа)
     * @param string $titleContacts - Id контактов для печати на веб странице
     */
    public function __construct(
        int $id_address,
        array $id_recipient,
        string $address,
        string $status,
        string $titleContacts
    ) {
        $this->id_address = $id_address;
        $this->id_recipient = $id_recipient;
        $this->address = $address;
        $this->status = $status;
        $this->titleContacts = $titleContacts;
    }

    /**
     * @return int
     */
    public function getIdAddress(): int
    {
        return $this->id_address;
    }

    /**
     * @return array
     */
    public function getRecipients(): array
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

    /**
     * @return string
     */
    public function getTitleContacts(): string
    {
        return $this->titleContacts;
    }
}
