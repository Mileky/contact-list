<?php

namespace DD\ContactList\Entity;

use DD\ContactList\Exception;

/**
 * Клиент
 */
final class Customer extends AbstractContact
{
    /**
     * Контактный телефон клиента
     *
     * @var string
     */
    private string $contractNumber;
    /**
     * Средняя сумма по транзакциям клиента
     *
     * @var int
     */
    private int $averageTransactionAmount;
    /**
     * Скидка клиента
     *
     * @var string
     */
    private string $discount;
    /**
     * Время в которое можно беспокоить клиента
     *
     * @var string
     */
    private string $timeToCall;

    /**
     * @param int    $id_recipient             - id Получателя
     * @param string $full_name                - Полное имя получателя
     * @param string $birthday                 - Дата рождения получателя
     * @param string $profession               - Профессия получателя
     * @param string $contractNumber           - Контактный телефон клиента
     * @param int    $averageTransactionAmount - Средняя сумма по транзакциям клиента
     * @param string $discount                 - Скидка клиента
     * @param string $timeToCall               - Время в которое можно беспокоить клиента
     */
    public function __construct(
        int $id_recipient,
        string $full_name,
        string $birthday,
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

    public function jsonSerialize(): array
    {
        $jsonData = parent::jsonSerialize();
        $jsonData['contract_number'] = $this->contractNumber;
        $jsonData['average_transaction_amount'] = $this->averageTransactionAmount;
        $jsonData['discount'] = $this->discount;
        $jsonData['time_to_call'] = $this->timeToCall;
        return $jsonData;
    }

    /**
     * Создание сущности "Клиент" из массива
     *
     * @param array $data
     *
     * @return Customer
     */
    public static function createFromArray(array $data): Customer
    {
        $requiredFields = [
            'id_recipient',
            'full_name',
            'birthday',
            'profession',
            'messengers',
            'contract_number',
            'average_transaction_amount',
            'discount',
            'time_to_call'
        ];

        $missingFields = array_diff($requiredFields, array_keys($data));

        if (count($missingFields) > 0) {
            $errMsg = sprintf('Отсутствуют обязательные элементы: %s', implode(',', $missingFields));
            throw new Exception\InvalidDataStructureException($errMsg);
        }
        return new Customer(
            $data['id_recipient'],
            $data['full_name'],
            $data['birthday'],
            $data['profession'],
            $data['messengers'],
            $data['contract_number'],
            $data['average_transaction_amount'],
            $data['discount'],
            $data['time_to_call']
        );
    }
}
