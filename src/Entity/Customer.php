<?php

namespace DD\ContactList\Entity;

use DateTimeImmutable;
use DD\ContactList\Exception;
use Doctrine\ORM\Mapping as ORM;

/**
 * Клиент
 *
 * @ORM\Entity
 * @ORM\Table(
 *     name="contacts_customers",
 *     indexes={
 *          @ORM\Index(name="contacts_customers_average_transaction_amount_idx", columns={"average_transaction_amount"}),
 *          @ORM\Index(name="contacts_customers_discount_idx", columns={"discount"}),
 *          @ORM\Index(name="contacts_customers_time_to_call_idx", columns={"time_to_call"})
 *     },
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="contacts_customers_contract_number_unq", columns={"contract_number"})
 *     }
 * )
 */
class Customer extends AbstractContact
{
    /**
     * Контактный телефон клиента
     *
     * @ORM\Column(name="contract_number", type="integer", nullable=false)
     *
     * @var string
     */
    private string $contractNumber;

    /**
     * Средняя сумма по транзакциям клиента
     *
     * @ORM\Column(name="average_transaction_amount", type="integer", nullable=false)
     *
     * @var int
     */
    private int $averageTransactionAmount;

    /**
     * Скидка клиента
     *
     * @ORM\Column(name="discount", type="string", length=5, nullable=false)
     *
     * @var string
     */
    private string $discount;

    /**
     * Время в которое можно беспокоить клиента
     *
     * @ORM\Column(name="time_to_call", type="string", length=35, nullable=false)
     *
     * @var string
     */
    private string $timeToCall;

    /**
     * @param int $id_recipient             - id Получателя
     * @param string $full_name             - Полное имя получателя
     * @param DateTimeImmutable $birthday   - Дата рождения получателя
     * @param string $profession            - Профессия получателя
     * @param string $contractNumber        - Контактный телефон клиента
     * @param int $averageTransactionAmount - Средняя сумма по транзакциям клиента
     * @param string $discount              - Скидка клиента
     * @param string $timeToCall            - Время в которое можно беспокоить клиента
     */
    public function __construct(
        int $id_recipient,
        string $full_name,
        DateTimeImmutable $birthday,
        string $profession,
        array $messengers,
        string $contractNumber,
        int $averageTransactionAmount,
        string $discount,
        string $timeToCall
    ) {
        parent::__construct($id_recipient, $full_name, $birthday, $profession, $messengers);
        $this->contractNumber = $contractNumber;
        $this->averageTransactionAmount = $averageTransactionAmount;
        $this->discount = $discount;
        $this->timeToCall = $timeToCall;
    }


    /**
     * Возвращает контактный телефон
     *
     * @return string
     */
    public function getContractNumber(): string
    {
        return $this->contractNumber;
    }

    /**
     * Устанавливает контактный телефон
     *
     * @param string $contractNumber
     *
     * @return Customer
     */
    public function setContractNumber(string $contractNumber): Customer
    {
        $this->contractNumber = $contractNumber;
        return $this;
    }

    /**
     * Возвращает среднюю сумму по операциям клиента
     *
     * @return int
     */
    public function getAverageTransactionAmount(): int
    {
        return $this->averageTransactionAmount;
    }

    /**
     * Устанавливает среднюю сумму по операциям
     *
     * @param int $averageTransactionAmount
     *
     * @return Customer
     */
    public function setAverageTransactionAmount(int $averageTransactionAmount): Customer
    {
        $this->averageTransactionAmount = $averageTransactionAmount;
        return $this;
    }

    /**
     * Возвращает скидку по клиенту
     *
     * @return string
     */
    public function getDiscount(): string
    {
        return $this->discount;
    }

    /**
     * Устанавливает скидку
     *
     * @param string $discount
     *
     * @return Customer
     */
    public function setDiscount(string $discount): Customer
    {
        $this->discount = $discount;
        return $this;
    }

    /**
     * Возвращает время беспокоиства
     *
     * @return string
     */
    public function getTimeToCall(): string
    {
        return $this->timeToCall;
    }

    /**
     * Устанавливает время беспокоиства
     *
     * @param string $time_to_call
     *
     * @return Customer
     */
    public function setTimeToCall(string $time_to_call): Customer
    {
        $this->timeToCall = $time_to_call;
        return $this;
    }
}
