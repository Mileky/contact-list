<?php

namespace DD\ContactList\Infrastructure\Console\Output;

/**
 * Интерфейс отвечающий за вывод данных в консоль
 */
interface OutputInterface
{
    /**
     * Выводит информацию в консоль
     *
     * @param string $text
     *
     * @return void
     */
    public function print(string $text): void;

}