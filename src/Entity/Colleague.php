<?php

namespace DD\ContactList\Entity;

use DD\ContactList\Exception;

/**
 * Коллега
 */
final class Colleague extends AbstractContact
{
    /**
     * Отдел коллеги
     *
     * @var string
     */
    private string $department;

    /**
     * Должность коллеги
     *
     * @var string
     */
    private string $position;

    /**
     * Номер кабинета
     *
     * @var string
     */
    private string $roomNumber;

    /**
     * @param int    $id_recipient - id Получателя
     * @param string $full_name    - Полное имя получателя
     * @param string $birthday     - Дата рождения получателя
     * @param string $profession   - Профессия получателя
     * @param array  $messengers   - Данные о мессенджере, в котором есть пользователь
     * @param string $department   - Отдел коллеги
     * @param string $position     - Должность коллеги
     * @param string $roomNumber   - Номер кабинета
     */
    public function __construct(
        int $id_recipient,
        string $full_name,
        string $birthday,
        string $profession,
        array $messengers,
        string $department,
        string $position,
        string $roomNumber
    ) {
        parent::__construct($id_recipient, $full_name, $birthday, $profession, $messengers);
        $this->department = $department;
        $this->position = $position;
        $this->roomNumber = $roomNumber;
    }


    /**
     * Возвращает отдел
     *
     * @return string
     */
    public function getDepartment(): string
    {
        return $this->department;
    }

    /**
     * Устанавливает отдел
     *
     * @param string $department
     *
     * @return Colleague
     */
    public function setDepartment(string $department): Colleague
    {
        $this->department = $department;
        return $this;
    }

    /**
     * Возвращает должность
     *
     * @return string
     */
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * Устанавливает должность
     *
     * @param string $position
     *
     * @return Colleague
     */
    public function setPosition(string $position): Colleague
    {
        $this->position = $position;
        return $this;
    }

    /**
     * Возвращает номер кабинета
     *
     * @return string
     */
    public function getRoomNumber(): string
    {
        return $this->roomNumber;
    }

    /**
     * Устанавливает номер кабинета
     *
     * @param string $roomNumber
     *
     * @return Colleague
     */
    public function setRoomNumber(string $roomNumber): Colleague
    {
        $this->roomNumber = $roomNumber;
        return $this;
    }

    public function jsonSerialize(): array
    {
        $jsonData = parent::jsonSerialize();
        $jsonData['department'] = $this->department;
        $jsonData['position'] = $this->position;
        $jsonData['room_number'] = $this->roomNumber;
        return $jsonData;
    }

    /**
     * Создание сущности "Коллега" из массива
     *
     * @param array $data
     *
     * @return Colleague
     */
    public static function createFromArray(array $data): Colleague
    {
        $requiredFields = [
            'id_recipient',
            'full_name',
            'birthday',
            'profession',
            'messengers',
            'department',
            'position',
            'room_number'
        ];

        $missingFields = array_diff($requiredFields, array_keys($data));

        if (count($missingFields) > 0) {
            $errMsg = sprintf('Отсутствуют обязательные элементы: %s', implode(',', $missingFields));
            throw new Exception\InvalidDataStructureException($errMsg);
        }
        return new Colleague(
            $data['id_recipient'],
            $data['full_name'],
            $data['birthday'],
            $data['profession'],
            $data['messengers'],
            $data['department'],
            $data['position'],
            $data['room_number']
        );
    }

}