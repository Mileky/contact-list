<?php

namespace DD\ContactList\Service\SearchContactService;

/**
 * ДТО клиентов
 */
final class CustomerDto
{
    /**
     * Номер контракта
     *
     * @var string|null
     */
    private ?string $contractNumber;

    /**
     * Средняя сумма транзакций
     *
     * @var int|null
     */
    private ?int $averageTransactionAmount;

    /**
     * Скидка
     *
     * @var string|null
     */
    private ?string $discount;

    /**
     * Время для звонка
     *
     * @var string|null
     */
    private ?string $timeToCall;

    /**
     * @param string|null $contractNumber           - Номер контракта
     * @param int|null    $averageTransactionAmount - Средняя сумма транзакций
     * @param string|null $discount                 - Скидка
     * @param string|null $timeToCall               - Время для звонка
     */
    public function __construct(
        ?string $contractNumber,
        ?int $averageTransactionAmount,
        ?string $discount,
        ?string $timeToCall
    ) {
        $this->contractNumber = $contractNumber;
        $this->averageTransactionAmount = $averageTransactionAmount;
        $this->discount = $discount;
        $this->timeToCall = $timeToCall;
    }

    /**
     * @return string|null
     */
    public function getContractNumber(): ?string
    {
        return $this->contractNumber;
    }

    /**
     * @return int|null
     */
    public function getAverageTransactionAmount(): ?int
    {
        return $this->averageTransactionAmount;
    }

    /**
     * @return string|null
     */
    public function getDiscount(): ?string
    {
        return $this->discount;
    }

    /**
     * @return string|null
     */
    public function getTimeToCall(): ?string
    {
        return $this->timeToCall;
    }
}
