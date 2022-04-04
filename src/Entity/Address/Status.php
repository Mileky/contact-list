<?php

namespace DD\ContactList\Entity\Address;

use Doctrine\ORM\Mapping as ORM;
use DD\ContactList\Exception;

/**
 * Статус адреса
 *
 * @ORM\Entity
 * @ORM\Table(
 *     name="address_status",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="address_status_name_unq", columns={"name"})
 *     }
 * )
 */
class Status
{
    /**
     * Статус адреса - "дом"
     */
    public const STATUS_HOME = 'home';

    /**
     * Статус адреса - "работа"
     */
    public const STATUS_JOB = 'job';

    /**
     * Допустимые статусы
     */
    private const ALLOWED_STATUS = [
        self::STATUS_HOME,
        self::STATUS_JOB
    ];

    /**
     * Теневой id
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="address_status_id_seq")
     */
    private int $id = -1;

    /**
     * Статус
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     *
     * @var string
     */
    private string $name;

    /**
     * @param string $name - Статус
     */
    public function __construct(string $name)
    {
        $this->validate($name);
        $this->name = $name;
    }

    /**
     * Валидация статуса
     *
     * @param string $name
     *
     * @return void
     */
    private function validate(string $name): void
    {
        if (in_array($name, self::ALLOWED_STATUS) === false) {
            throw new Exception\RuntimeException("Некорректный статус адреса: $name");
        }
    }

    /**
     * Возвращает статус
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
