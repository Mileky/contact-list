<?php

namespace DD\ContactList\Infrastructure\Logger\Adapter;

use DD\ContactList\Infrastructure\Logger\AdapterInterface;

/**
 * Запись лога в файл
 */
class FileAdapter implements AdapterInterface
{
    /**
     * Путь до файла
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
    public function write(string $logLevel, string $msg): void
    {
        file_put_contents($this->pathToFile, "$msg\n", FILE_APPEND);
    }

}