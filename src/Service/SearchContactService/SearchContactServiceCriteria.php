<?php

namespace DD\ContactList\Service\SearchContactService;

/**
 * DTO входных данных
 */
final class SearchContactServiceCriteria
{
    /**
     * id контакта
     *
     * @var string|null
     */
    private ?string $id = null;

    /**
     * Полное имя контакта
     *
     * @var string|null
     */
    private ?string $fullName = null;

    /**
     * День рождения контакта
     *
     * @var string|null
     */
    private ?string $birthday = null;


    /**
     * Профессия контакта
     *
     * @var string|null
     */
    private ?string $profession = null;


    //Родственники

    /**
     * Тип родства
     *
     * @var string|null
     */
    private ?string $status = null;

    /**
     * Рингтон
     *
     * @var string|null
     */
    private ?string $ringtone = null;

    /**
     * Хоткей для набора
     *
     * @var string|null
     */
    private ?string $hotkey = null;


    //Клиенты

    /**
     * Номер контракта
     *
     * @var string|null
     */
    private ?string $contractNumber = null;

    /**
     * Средняя сумма транзакций
     *
     * @var int|null
     */
    private ?int $averageTransactionAmount = null;

    /**
     * Скидка
     *
     * @var string|null
     */
    private ?string $discount = null;

    /**
     * Время для звонка
     *
     * @var string|null
     */
    private ?string $timeToCall = null;


    //Коллеги

    /**
     * Отдел, в котором работает
     *
     * @var string|null
     */
    private ?string $department = null;

    /**
     * Должность
     *
     * @var string|null
     */
    private ?string $position = null;

    /**
     * Номер кабинета
     *
     * @var string|null
     */
    private ?string $roomNumber = null;

    /**
     * Категория контактов
     *
     * @var string|null
     */
    private ?string $category = null;

    /**
     * @return string|null
     */
    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    /**
     * @param string|null $birthday
     *
     * @return SearchContactServiceCriteria
     */
    public function setBirthday(?string $birthday): SearchContactServiceCriteria
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getProfession(): ?string
    {
        return $this->profession;
    }

    /**
     * @param string|null $profession
     *
     * @return SearchContactServiceCriteria
     */
    public function setProfession(?string $profession): SearchContactServiceCriteria
    {
        $this->profession = $profession;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     *
     * @return SearchContactServiceCriteria
     */
    public function setStatus(?string $status): SearchContactServiceCriteria
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRingtone(): ?string
    {
        return $this->ringtone;
    }

    /**
     * @param string|null $ringtone
     *
     * @return SearchContactServiceCriteria
     */
    public function setRingtone(?string $ringtone): SearchContactServiceCriteria
    {
        $this->ringtone = $ringtone;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHotkey(): ?string
    {
        return $this->hotkey;
    }

    /**
     * @param string|null $hotkey
     *
     * @return SearchContactServiceCriteria
     */
    public function setHotkey(?string $hotkey): SearchContactServiceCriteria
    {
        $this->hotkey = $hotkey;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getContractNumber(): ?string
    {
        return $this->contractNumber;
    }

    /**
     * @param string|null $contractNumber
     *
     * @return SearchContactServiceCriteria
     */
    public function setContractNumber(?string $contractNumber): SearchContactServiceCriteria
    {
        $this->contractNumber = $contractNumber;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getAverageTransactionAmount(): ?int
    {
        return $this->averageTransactionAmount;
    }

    /**
     * @param int|null $averageTransactionAmount
     *
     * @return SearchContactServiceCriteria
     */
    public function setAverageTransactionAmount(?int $averageTransactionAmount): SearchContactServiceCriteria
    {
        $this->averageTransactionAmount = $averageTransactionAmount;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDiscount(): ?string
    {
        return $this->discount;
    }

    /**
     * @param string|null $discount
     *
     * @return SearchContactServiceCriteria
     */
    public function setDiscount(?string $discount): SearchContactServiceCriteria
    {
        $this->discount = $discount;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTimeToCall(): ?string
    {
        return $this->timeToCall;
    }

    /**
     * @param string|null $timeToCall
     *
     * @return SearchContactServiceCriteria
     */
    public function setTimeToCall(?string $timeToCall): SearchContactServiceCriteria
    {
        $this->timeToCall = $timeToCall;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDepartment(): ?string
    {
        return $this->department;
    }

    /**
     * @param string|null $department
     *
     * @return SearchContactServiceCriteria
     */
    public function setDepartment(?string $department): SearchContactServiceCriteria
    {
        $this->department = $department;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPosition(): ?string
    {
        return $this->position;
    }

    /**
     * @param string|null $position
     *
     * @return SearchContactServiceCriteria
     */
    public function setPosition(?string $position): SearchContactServiceCriteria
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRoomNumber(): ?string
    {
        return $this->roomNumber;
    }

    /**
     * @param string|null $roomNumber
     *
     * @return SearchContactServiceCriteria
     */
    public function setRoomNumber(?string $roomNumber): SearchContactServiceCriteria
    {
        $this->roomNumber = $roomNumber;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     *
     * @return SearchContactServiceCriteria
     */
    public function setId(?string $id): SearchContactServiceCriteria
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    /**
     * @param string|null $fullName
     *
     * @return SearchContactServiceCriteria
     */
    public function setFullName(?string $fullName): SearchContactServiceCriteria
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param string|null $category
     *
     * @return SearchContactServiceCriteria
     */
    public function setCategory(?string $category): SearchContactServiceCriteria
    {
        $this->category = $category;
        return $this;
    }


}