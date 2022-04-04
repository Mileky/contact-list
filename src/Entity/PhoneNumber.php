<?php

namespace DD\ContactList\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Номер телефона
 *
 * @ORM\Entity
 * @ORM\Table(
 *     name="phone_number",
 *     indexes={
 *          @ORM\Index(name="phone_number_operator_idx", columns={"operator"}),
 *          @ORM\Index(name="phone_number_id_recipient_idx", columns={"id_recipient"})
 *     },
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="phone_number_phone_number_unq", columns={"phone_number"})
 *     }
 * )
 */
class PhoneNumber
{
    /**
     * ID номера телефона
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="phone_number_id_seq")
     * @ORM\Column(type="integer", name="id", nullable=false)
     *
     * @var int
     */
    private int $id;

    /**
     * Контакт
     *
     * @ORM\ManyToOne(targetEntity=\DD\ContactList\Entity\AbstractContact::class)
     * @ORM\JoinColumn (name="id_recipient", referencedColumnName="id")
     *
     * @var AbstractContact
     */
    private AbstractContact $recipients;

    /**
     * Номер телефона
     *
     * @ORM\Column(name="phone_number", type="string", length=12, nullable=false)
     *
     * @var string
     */
    private string $phoneNumber;

    /**
     * Оператор номера телефона
     *
     * @ORM\Column(name="operator", type="string", length=20, nullable=false)
     *
     * @var string
     */
    private string $operator;

    /**
     * @param int $id
     * @param AbstractContact $recipients
     * @param string $phoneNumber
     * @param string $operator
     */
    public function __construct(int $id, AbstractContact $recipients, string $phoneNumber, string $operator)
    {
        $this->id = $id;
        $this->recipients = $recipients;
        $this->phoneNumber = $phoneNumber;
        $this->operator = $operator;
    }

    /**
     * Возвращает id номера телефона
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Возвращает контакт
     *
     * @return AbstractContact
     */
    public function getRecipients(): AbstractContact
    {
        return $this->recipients;
    }

    /**
     * Возвращает Номер телефона
     *
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * Возвращает Оператор номера телефона
     *
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }
}