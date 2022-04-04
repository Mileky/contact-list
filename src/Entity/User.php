<?php

namespace DD\ContactList\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Пользователь системы
 * @ORM\MappedSuperclass()
 */
class User
{
    /**
     * ID юзера
     *
     * @ORM\Id()
     * @ORM\SequenceGenerator(sequenceName="user_id_seq")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="id", type="integer", nullable=false)
     *
     * @var int
     */
    private int $id;

    /**
     * Логин юзера
     *
     * @ORM\Column(name="login", type="string", length=50, nullable=false)
     *
     * @var string
     */
    private string $login;

    /**
     * Пароль юзера
     *
     * @ORM\Column(name="password", type="string", length=60, nullable=false)
     *
     * @var string
     */
    private string $password;

    /**
     * @param int    $id       - ID юзера
     * @param string $login    - Логин юзера
     * @param string $password - Пароль юзера
     */
    public function __construct(int $id, string $login, string $password)
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * Возвращает ID юзера
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Возвращает Логин юзера
     *
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * Возвращает Пароль юзера
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
