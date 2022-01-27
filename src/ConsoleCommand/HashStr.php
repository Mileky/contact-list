<?php

namespace DD\ContactList\ConsoleCommand;

use DD\ContactList\Infrastructure\Console\CommandInterface;
use DD\ContactList\Infrastructure\Console\Output\OutputInterface;

class HashStr implements CommandInterface
{

    /**
     * Компонент вывода данных в консоль
     *
     * @var OutputInterface
     */
    private OutputInterface $output;

    /**
     * @param OutputInterface $output - Компонент вывода данных в консоль
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }


    /**
     * @inheritDoc
     */
    public static function getShortOptions(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public static function getLongOptions(): array
    {
        return [
            'data:',
        ];
    }

    /**
     * @inheritDoc
     */
    public function __invoke(array $params): void
    {
        if (false === array_key_exists('data', $params)) {
            $msg = 'Data for hashing is not specified';
        } elseif (false === is_string($params['data'])) {
            $msg = 'Hash data is not in the correct format';
        } else {
            $msg = password_hash($params['data'], PASSWORD_DEFAULT);
        }

        $this->output->print($msg . "\n");
    }
}