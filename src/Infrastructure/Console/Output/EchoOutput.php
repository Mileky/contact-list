<?php

namespace DD\ContactList\Infrastructure\Console\Output;

/**
 * Класс реализующий вывод информации в консоль посредством использования echo
 */
class EchoOutput implements OutputInterface
{

    /**
     * @inheritDoc
     */
    public function print(string $text): void
    {
        echo $text;
    }
}