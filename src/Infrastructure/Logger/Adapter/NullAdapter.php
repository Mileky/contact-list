<?php

namespace DD\ContactList\Infrastructure\Logger\Adapter;

use DD\ContactList\Infrastructure\Logger\AdapterInterface;

/**
 * Логирование в никуда
 */
class NullAdapter implements AdapterInterface
{

    /**
     * @inheritDoc
     */
    public function write(string $logLevel, string $msg): void
    {
    }
}