<?php

namespace DD\ContactList\Infrastructure\Logger\NullLogger;

use DD\ContactList\Infrastructure\Logger\LoggerInterface;


/**
 *  Логгирует в "никуда"
 */
class Logger implements LoggerInterface
{
    /**
     * @inheritDoc
     */
    public function log(string $msg): void
    {
    }

}