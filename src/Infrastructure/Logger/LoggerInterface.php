<?php

namespace DD\ContactList\Infrastructure\Logger;

/**
 *  Интерфейс логирования
 */
interface LoggerInterface
{
    /**
     * апись в логи сообщение
     *
     * @param string $msg - логируемое сообщение
     *
     * @return void
     */
    public function log(string $msg): void;
}