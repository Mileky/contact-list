<?php

namespace DD\ContactList\Infrastructure\Console;

use DD\ContactList\Infrastructure\Exception;
use DD\ContactList\Infrastructure\Console\Output\EchoOutput;
use DD\ContactList\Infrastructure\Console\Output\OutputInterface;
use DD\ContactList\Infrastructure\DI\ContainerInterface;
use Throwable;

class AppConsole
{
    /**
     * Ключ - имя команды. Значение - класс консольной команды
     *
     * @var array
     */
    private array $commands;

    /**
     * Компонент отвечающий за вывод данных в консоль
     *
     * @var OutputInterface|null
     */
    private ?OutputInterface $output = null;

    /**
     * di контейнер
     *
     * @var ContainerInterface|null
     */
    private ?ContainerInterface $diContainer = null;

    /**
     * Фабрика реализующая логику создания рендера
     *
     * @var callable
     */
    private $outputFactory;

    /**
     * Фабрика реализующая логику создания di контейнера
     *
     * @var callable
     */
    private $diContainerFactory;

    /**
     * @param array $commands              - Ключ - имя команды. Значение - класс консольной команды или данные типа
     *                                     callable
     * @param callable $outputFactory      - Фабрика реализующая логику создания рендера
     * @param callable $diContainerFactory - Фабрика реализующая логику создания di контейнера
     */
    public function __construct(array $commands, callable $outputFactory, callable $diContainerFactory)
    {
        $this->commands = $commands;
        $this->outputFactory = $outputFactory;
        $this->diContainerFactory = $diContainerFactory;

        $this->initiateErrorHandling();
    }

    /**
     * Инициализация обработки ошибок
     *
     * @return void
     */
    private function initiateErrorHandling(): void
    {
        set_error_handler(static function (int $errNom, string $errStr/**, string $errFile, int $errLine**/) {
            throw new Exception\RuntimeException($errStr);
        });
    }

    /**
     * Возвращает компонент отвечающий за вывод данных в консоль
     *
     * @return OutputInterface
     */
    private function getOutput(): OutputInterface
    {
        if (null === $this->output) {
            $this->output = ($this->outputFactory)($this->getDiContainer());
        }
        return $this->output;
    }

    /**
     * @return ContainerInterface
     */
    private function getDiContainer(): ContainerInterface
    {
        if (null === $this->diContainer) {
            $this->diContainer = ($this->diContainerFactory)();
        }
        return $this->diContainer;
    }


    public function dispatch(string $commandName = null, array $params = null): void
    {
        $output = null;
        try {
            $output = $this->getOutput();

            $commandName = $commandName ?? $this->getCommandName();

            if (null === $commandName) {
                throw new Exception\RuntimeException('Command name must be specified');
            }

            if (false === array_key_exists($commandName, $this->commands)) {
                throw new Exception\RuntimeException("Unknown command: '$commandName'");
            }

            if (false === is_string($this->commands[$commandName]) || false === is_subclass_of(
                    $this->commands[$commandName],
                    CommandInterface::class,
                    true
                )) {
                throw new Exception\RuntimeException("There is no valid handler for command: '$commandName'");
            }

            $command = $this->getDiContainer()->get($this->commands[$commandName]);

            $params = $params ?? $this->getCommandParams($this->commands[$commandName]);

            $command($params);
        } catch (Throwable $e) {
            $output = $output ?? new EchoOutput();
            $output->print("Error: {$e->getMessage()}\n");
        }
    }

    /**
     * Возвращает имя команды
     *
     * @return string|null
     */
    private function getCommandName(): ?string
    {
        $options = getopt('', ['command:']);
        $command = null;

        if (is_array($options) && array_key_exists('command', $options) && is_string($options['command'])) {
            $command = $options['command'];
        }

        return $command;
    }

    /**
     * Возвращает параметры для команды
     *
     * @param string $commandName
     *
     * @return array
     */
    private function getCommandParams(string $commandName): array
    {
        $longOptions = call_user_func("$commandName::getLongOptions");
        $shortOptions = call_user_func("$commandName::getShortOptions");

        $options = getopt($shortOptions, $longOptions);

        return is_array($options) ? $options : [];
    }

}