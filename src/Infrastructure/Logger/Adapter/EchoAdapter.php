<?php

namespace DD\ContactList\Infrastructure\Logger\Adapter;

use DD\ContactList\Infrastructure\Logger\AdapterInterface;

/**
 * Логирование в консоль
 */
class EchoAdapter implements AdapterInterface
{

    /**
     * @inheritDoc
     */
    public function write(string $logLevel, string $msg): void
    {
        echo "$msg\n";
    }
}