<?php

namespace DD\ContactList\ValueObject;

use DD\ContactList\Entity\AbstractContact;
use DD\ContactList\Exception;
use Doctrine\ORM\Mapping as ORM;

/**
 * Объект-значение Мессенджер
 *
 * @ORM\Entity
 * @ORM\Table(
 *     name="messengers",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="messengers_username_unq", columns={"id_recipient"})}
 * )
 */
class Messenger
{
    private const NAME_MESSENGERS = [
        'telegram',
        'viber',
        'whatsapp'
    ];

    /**
     * Теневой id
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="messengers_id_seq")
     *
     */
    private ?int $id = null;

    /**
     * Мессенджер
     *
     * @ORM\Column(name="type_messenger", type="string", length=50, nullable=false)
     *
     * @var string
     */
    private string $typeMessenger;

    /**
     * Имя пользователя в мессенджере
     *
     * @ORM\Column(name="username", type="string", length=50, nullable=false)
     *
     * @var string
     */
    private string $username;

    /**
     * Ассоциация с контактом
     *
     * @ORM\ManyToOne(
     *     targetEntity=\DD\ContactList\Entity\AbstractContact::class, inversedBy="messengers",
     *     fetch="EAGER"
     * )
     * @ORM\JoinColumn(name="id_recipient", referencedColumnName="id")
     *
     * @var AbstractContact|null
     */
    private ?AbstractContact $abstractContact = null;

    /**
     * @param string $typeMessenger - Название мессенджера
     * @param string $username      - Имя пользователя в мессенджере
     */
    public function __construct(string $typeMessenger, string $username)
    {
        $this->validateTypeMessenger($typeMessenger);
        $this->validateUsername($username);
        $this->typeMessenger = $typeMessenger;
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getTypeMessenger(): string
    {
        return $this->typeMessenger;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    private function validateTypeMessenger($typeMessenger): void
    {
        if (!ctype_alpha($typeMessenger)) {
            throw new Exception\DomainException('Некорректное название мессенджера');
        }

        if (false === in_array($typeMessenger, self::NAME_MESSENGERS, true)) {
            throw new Exception\RuntimeException('Неверное имя мессенджера');
        }
    }

    private function validateUsername(string $username): void
    {
        if (40 < strlen($username)) {
            throw new Exception\RuntimeException('Некорректная длина имени пользователя');
        }
        if (1 !== preg_match('/^[a-zA-Z][a-zA-Z0-9-]+$/', $username)) {
            throw new Exception\RuntimeException('Некорректное имя пользователя');
        }
    }
}
