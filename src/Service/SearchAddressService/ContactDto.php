<?php

namespace DD\ContactList\Service\SearchAddressService;

class ContactDto
{
    /**
     * Id контакта
     *
     * @var int
     */
    private int $id;

    /**
     * Полное имя контакта
     *
     * @var string
     */
    private string $fullName;

    /**
     * День рождения контакта
     *
     * @var string
     */
    private string $birthday;

    /**
     * Профессия контакта
     *
     * @var string|null
     */
    private ?string $profession;

    /**
     * @param int         $id
     * @param string      $fullName
     * @param string      $birthday
     * @param string|null $profession
     */
    public function __construct(int $id, string $fullName, string $birthday, ?string $profession)
    {
        $this->id = $id;
        $this->fullName = $fullName;
        $this->birthday = $birthday;
        $this->profession = $profession;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @return string
     */
    public function getBirthday(): string
    {
        return $this->birthday;
    }

    /**
     * @return string|null
     */
    public function getProfession(): ?string
    {
        return $this->profession;
    }

}