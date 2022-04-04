<?php

namespace DD\ContactList\Entity;

use DateTimeImmutable;
use DD\ContactList\Exception;
use Doctrine\ORM\Mapping as ORM;

/**
 * Коллега
 *
 * @ORM\Entity
 * @ORM\Table(
 *     name="contacts_colleagues",
 *     indexes={
 *          @ORM\Index(name="contacts_colleagues_department_idx", columns={"department"}),
 *          @ORM\Index(name="contacts_colleagues_position_idx", columns={"position"}),
 *          @ORM\Index(name="contacts_colleagues_room_number_idx", columns={"room_number"})
 *     }
 * )
 */
class Colleague extends AbstractContact
{
    /**
     * Отдел коллеги
     *
     * @ORM\Column(name="department", type="string", length=40, nullable=false)
     *
     * @var string
     */
    private string $department;

    /**
     * Должность коллеги
     *
     * @ORM\Column(name="position", type="string", length=40, nullable=false)
     *
     * @var string
     */
    private string $position;

    /**
     * Номер кабинета
     *
     * @ORM\Column(name="room_number", type="integer", nullable=false)
     *
     * @var string
     */
    private string $roomNumber;

    /**
     * @param int $id_recipient           - id Получателя
     * @param string $full_name           - Полное имя получателя
     * @param DateTimeImmutable $birthday - Дата рождения получателя
     * @param string $profession          - Профессия получателя
     * @param array $messengers           - Данные о мессенджере, в котором есть пользователь
     * @param string $department          - Отдел коллеги
     * @param string $position            - Должность коллеги
     * @param string $roomNumber          - Номер кабинета
     */
    public function __construct(
        int $id_recipient,
        string $full_name,
        DateTimeImmutable $birthday,
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
            'id',
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
            $data['id'],
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
