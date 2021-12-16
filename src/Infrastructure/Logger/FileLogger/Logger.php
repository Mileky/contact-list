<?php

namespace DD\ContactList\Infrastructure\Logger\FileLogger;

use DD\ContactList\Infrastructure\Logger\LoggerInterface;

/**
 *  Логирует в файл
 */
class Logger implements LoggerInterface
{
    /**
     * Путь до файла где пишутся логи
     *
     * @var string
     */
    private string $pathToFile;

    /**
     * Конструктор пути до файла
     *
     * @param string $pathToFile
     */
    public function __construct(string $pathToFile)
    {
        $this->pathToFile = $pathToFile;
    }

    /**
     * @inheritDoc
     */
    public function log(string $msg): void
    {
        file_put_contents($this->pathToFile, "$msg\n", FILE_APPEND);
    }
}