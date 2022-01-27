<?php

namespace DD\ContactList\Service\SearchContactService;

/**
 * ДТО Контактов
 */
final class ContactDto
{
    /**
     * Категория контакта "Знакомый"
     */
    public const TYPE_RECIPIENT = 'recipient';

    /**
     * Категория контакта "Родственник"
     */
    public const TYPE_KINSFOLK = 'kinsfolk';

    /**
     * Категория контакта "Клиент"
     */
    public const TYPE_CUSTOMER = 'customer';

    /**
     * Категория контакта "Коллега"
     */
    public const TYPE_COLLEAGUE = 'colleague';

    /**
     * Тип контакта
     *
     * @var string
     */
    private string $type;

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
     * Дто с данными родственников
     *
     * @var KinsfolkDto|null
     */
    private ?KinsfolkDto $kinsfolkData;

    /**
     * Дто с данными клиентов
     *
     * @var CustomerDto|null
     */
    private ?CustomerDto $customerData;

    /**
     * Дто с данными коллег
     *
     * @var ColleagueDto|null
     */
    private ?ColleagueDto $colleagueData;

    /**
     * @param string            $type          - Тип контакта
     * @param int               $id            - Id контакта
     * @param string            $fullName      - Полное имя контакта
     * @param string            $birthday      - День рождения контакта
     * @param string|null       $profession    - Профессия контакта
     * @param KinsfolkDto|null  $kinsfolkData  - Данные родственников
     * @param CustomerDto|null  $customerData  - Данные клиентов
     * @param ColleagueDto|null $colleagueData - Данные коллег
     */
    public function __construct(
        string $type,
        int $id,
        string $fullName,
        string $birthday,
        ?string $profession,
        ?KinsfolkDto $kinsfolkData,
        ?CustomerDto $customerData,
        ?ColleagueDto $colleagueData
    ) {
        $this->type = $type;
        $this->id = $id;
        $this->fullName = $fullName;
        $this->birthday = $birthday;
        $this->profession = $profession;
        $this->kinsfolkData = $kinsfolkData;
        $this->customerData = $customerData;
        $this->colleagueData = $colleagueData;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
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

    /**
     * @return KinsfolkDto|null
     */
    public function getKinsfolkData(): ?KinsfolkDto
    {
        return $this->kinsfolkData;
    }

    /**
     * @return CustomerDto|null
     */
    public function getCustomerData(): ?CustomerDto
    {
        return $this->customerData;
    }

    /**
     * @return ColleagueDto|null
     */
    public function getColleagueData(): ?ColleagueDto
    {
        return $this->colleagueData;
    }


}