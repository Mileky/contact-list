<?php

namespace DD\ContactList\Service\SearchAddressService;

class SearchAddressCriteria
{
    /**
     * Id адреса
     *
     * @var int
     */
    private ?int $id_address = null;

    /**
     * Id контакта
     *
     * @var int
     */
    private ?int $id_recipient = null;

    /**
     * Адрес контакта
     *
     * @var string
     */
    private ?string $address = null;

    /**
     * Статус адреса (дом/работа)
     *
     * @var string
     */
    private ?string $status = null;

    /**
     * @return int
     */
    public function getIdAddress(): ?int
    {
        return $this->id_address;
    }

    /**
     * @param int $id_address
     *
     * @return SearchAddressCriteria
     */
    public function setIdAddress(?int $id_address): SearchAddressCriteria
    {
        $this->id_address = $id_address;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdRecipient(): ?int
    {
        return $this->id_recipient;
    }

    /**
     * @param int $id_recipient
     *
     * @return SearchAddressCriteria
     */
    public function setIdRecipient(int $id_recipient): SearchAddressCriteria
    {
        $this->id_recipient = $id_recipient;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     *
     * @return SearchAddressCriteria
     */
    public function setAddress(string $address): SearchAddressCriteria
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return SearchAddressCriteria
     */
    public function setStatus(string $status): SearchAddressCriteria
    {
        $this->status = $status;
        return $this;
    }
}
