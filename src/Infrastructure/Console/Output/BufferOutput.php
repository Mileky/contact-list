<?php

namespace DD\ContactList\Infrastructure\Console\Output;

/**
 * Реализация вывода данных в буфер. Класс предназначен для тестирования консольных приложений
 */
class BufferOutput implements OutputInterface
{
    /**
     * Буфер для хранения результатов выводимых в консоль
     *
     * @var array
     */
    private array $buffer = [];

    /**
     * @inheritDoc
     */
    public function print(string $text): void
    {
        $this->buffer[] = $text;
    }

    /**
     * Возвращает буфер вывода
     *
     * @return array
     */
    public function getBuffer(): array
    {
        return $this->buffer;
    }
}