<?php

namespace DD\ContactList\Infrastructure\Logger\EchoLogger;

require_once __DIR__ . '/../LoggerInterface.php';

use DD\ContactList\Infrastructure\Logger\LoggerInterface;

/**
 *  Логирует в консоль с помощью echo
 */
class Logger implements LoggerInterface
{

    /**
     * @inheritDoc
     */
    public function log(string $msg): void
    {
        echo "$msg\n";
    }
}