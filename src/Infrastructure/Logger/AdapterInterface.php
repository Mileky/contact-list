<?php

namespace DD\ContactList\Infrastructure\Logger;

/**
 * Адаптер для записи логов
 */
interface AdapterInterface
{
    /**
     * Запись в лог
     *
     * @param string $logLevel - уровень логируемого сообщения
     * @param string $msg      - сообщение для записи в лог
     *
     * @return void
     */
    public function write(string $logLevel, string $msg): void;
}