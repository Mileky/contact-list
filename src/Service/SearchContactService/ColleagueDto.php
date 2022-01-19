<?php

namespace DD\ContactList\Service\SearchContactService;

/**
 * ДТО Коллег
 */
final class ColleagueDto
{
    /**
     * Отдел, в котором работает
     *
     * @var string|null
     */
    private ?string $department;

    /**
     * Должность
     *
     * @var string|null
     */
    private ?string $position;

    /**
     * Номер кабинета
     *
     * @var string|null
     */
    private ?string $roomNumber;

    /**
     * @param string|null $department
     * @param string|null $position
     * @param string|null $roomNumber
     */
    public function __construct(?string $department, ?string $position, ?string $roomNumber)
    {
        $this->department = $department;
        $this->position = $position;
        $this->roomNumber = $roomNumber;
    }

    /**
     * @return string|null
     */
    public function getDepartment(): ?string
    {
        return $this->department;
    }

    /**
     * @return string|null
     */
    public function getPosition(): ?string
    {
        return $this->position;
    }

    /**
     * @return string|null
     */
    public function getRoomNumber(): ?string
    {
        return $this->roomNumber;
    }


}