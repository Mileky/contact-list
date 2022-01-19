<?php

namespace DD\ContactList\Infrastructure\Console;

/**
 * Консольная команда
 */
interface CommandInterface
{
    /**
     * Возвращает конфиг описывающий короткие опции программы команды
     *
     * @return string
     */
    public static function getShortOptions(): string;

    /**
     * Возвращает конфиг описывающий длинные опции программы команды
     *
     * @return array
     */
    public static function getLongOptions(): array;


    /**
     * Запуск консольной команды
     *
     * @param array $params - параметры консольной команды
     *
     * @return void
     */
    public function __invoke(array $params): void;
}