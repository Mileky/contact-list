<?php

namespace DD\ContactList\Entity;

use DateTimeImmutable;
use DD\ContactList\Exception;
use Doctrine\ORM\Mapping as ORM;

/**
 * Знакомый
 *
 * @ORM\Entity
 * @ORM\Table(name="contacts_recipients")
 */
class Recipient extends AbstractContact
{
    /**
     * Прозвище\кличка знакомого
     *
     * @ORM\Column(type="string", name="nickname", nullable=true, length=50)
     *
     * @var string|null
     */
    private ?string $nickname;

    /**
     * @param int $id_recipient           - id Получателя
     * @param string $full_name           - Полное имя получателя
     * @param DateTimeImmutable $birthday - Дата рождения получателя
     * @param string $profession          - Профессия получателя
     * @param array $messengers           - Данные о мессенджере, в котором есть пользователь
     * @param string|null $nickname       - Прозвище\кличка знакомого
     */
    public function __construct(
        int $id_recipient,
        string $full_name,
        DateTimeImmutable $birthday,
        string $profession,
        array $messengers,
        string $nickname = null
    ) {
        parent::__construct($id_recipient, $full_name, $birthday, $profession, $messengers);
        $this->nickname = $nickname;
    }

    /**
     * Возвращает Прозвище\кличка знакомого
     *
     * @return string
     */
    public function getNickname(): ?string
    {
        return $this->nickname;
    }
}
