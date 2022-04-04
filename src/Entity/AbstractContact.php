<?php

namespace DD\ContactList\Entity;

use DateTimeImmutable;
use DD\ContactList\ValueObject\Messenger;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DD\ContactList\Exception;

/**
 * Контакт
 *
 * @ORM\Entity(repositoryClass=\DD\ContactList\Repository\ContactDoctrineRepository::class)
 * @ORM\Table(
 *     name="contacts",
 *     indexes={
 *          @ORM\Index(name="contacts_birthday_idx", columns={"birthday"}),
 *          @ORM\Index(name="contacts_category_idx", columns={"category"}),
 *          @ORM\Index(name="contacts_full_name_idx", columns={"full_name"}),
 *          @ORM\Index(name="contacts_profession_idx", columns={"profession"})
 *     }
 * )
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="category", type="string")
 * @ORM\DiscriminatorMap({
 *          "recipients" = \DD\ContactList\Entity\Recipient::class,
 *          "kinsfolk" = \DD\ContactList\Entity\Kinsfolk::class,
 *          "customers" = \DD\ContactList\Entity\Customer::class,
 *          "colleagues" = \DD\ContactList\Entity\Colleague::class
 *     })
 */
abstract class AbstractContact
{
    /**
     * id Получателя
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="contacts_id_seq")
     * @ORM\Column(type="integer", name="id", nullable=false)
     *
     * @var int
     */
    private int $id;

    /**
     * Полное имя получателя
     *
     * @ORM\Column(type="string", name="full_name", nullable=false, length=255)
     *
     * @var string
     */
    private string $fullName;

    /**
     * Дата рождения получателя
     *
     * @ORM\Column(type="date_immutable", name="birthday", nullable=false)
     *
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $birthday;

    /**
     * Профессия получателя
     *
     * @ORM\Column(type="string", name="profession", nullable=false, length=100)
     *
     * @var string
     */
    private string $profession;

    /**
     * Данные о мессенджере, в котором есть пользователь
     *
     * @ORM\OneToMany(targetEntity=\DD\ContactList\ValueObject\Messenger::class, mappedBy="abstractContact")
     *
     * @var Messenger[]|Collection
     */
    protected Collection $messengers;

    /**
     * @param int $id_recipient
     * @param string $full_name
     * @param DateTimeImmutable $birthday
     * @param string $profession
     * @param Messenger[] $messengers - Данные о мессенджере, в котором есть пользователь
     */
    public function __construct(
        int $id_recipient,
        string $full_name,
        DateTimeImmutable $birthday,
        string $profession,
        array $messengers
    ) {
        $this->id = $id_recipient;
        $this->fullName = $full_name;
        $this->birthday = $birthday;
        $this->profession = $profession;

        foreach ($messengers as $messenger) {
            if (!$messenger instanceof Messenger) {
                throw new Exception\DomainException('Некорректный формат данных о мессенджере');
            }
        }

        $this->messengers = new ArrayCollection($messengers);
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
     * @return DateTimeImmutable
     */
    public function getBirthday(): DateTimeImmutable
    {
        return $this->birthday;
    }

    /**
     * @return string
     */
    public function getProfession(): string
    {
        return $this->profession;
    }

    /**
     * @return Messenger[]
     */
    public function getMessengers(): array
    {
        return $this->messengers->toArray();
    }
}
