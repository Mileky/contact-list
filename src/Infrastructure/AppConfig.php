<?php

namespace DD\ContactList\Infrastructure;

use DD\ContactList\Exception;

/**
 *  Конфиг приложения
 */
class AppConfig
{
    /**
     * Путь до файла с данными о получателях
     * @var string
     */
    private string $pathToRecipients;
    /**
     * Путь до файла с данными о родне
     * @var string
     */
    private string $pathToKinsfolk;
    /**
     * Путь до файла с данными о клиентах
     * @var string
     */
    private string $pathToCustomers;
    /**
     * Путь до файла с данными о коллегах
     * @var string
     */
    private string $pathToColleagues;

    /**
     * Путь до файла с данными о адресах контактов
     *
     * @var string
     */
    private string $pathToAddress;

    /**
     * Путь до контактного листа
     *
     * @var string
     */
    private string $pathToContactList;

    /**
     * Путь до файла с данными о логах
     * @var string
     */
    private string $pathToLogFile;

    /**
     * Тип логера
     * @var string
     */
    private string $loggerType;

    /**
     * Сокрытие сообщений о ошибках
     * @var bool
     */
    private bool $hideErrorMessage;

    /**
     * Возвращает флаг, указывающий что ужно скрывать сообщения о ошибках
     *
     * @return bool
     */
    public function isHideErrorMessage(): bool
    {
        return $this->hideErrorMessage;
    }

    /**
     * Устанавливает фалг что нужно скрывать сообщения о ошибках
     *
     * @param bool $hideErrorMessage
     *
     * @return AppConfig
     */
    private function setHideErrorMessage(bool $hideErrorMessage): AppConfig
    {
        $this->hideErrorMessage = $hideErrorMessage;
        return $this;
    }


    /**
     * Возвращает тип логера
     * @return string
     */
    public function getLoggerType(): string
    {
        return $this->loggerType;
    }

    /**
     * Устанавливает тип логера
     *
     * @param string $loggerType
     *
     * @return AppConfig
     */
    private function setLoggerType(string $loggerType): AppConfig
    {
        $this->loggerType = $loggerType;
        return $this;
    }

    /**
     * Возвращает путь до файла с логами
     *
     * @return string
     */
    public function getPathToLogFile(): string
    {
        return $this->pathToLogFile;
    }


    /**
     * Устанавливает путь до файла логов
     *
     * @param string $pathToLogFile - путь до файла с логами
     *
     * @return AppConfig
     */
    private function setPathToLogFile(string $pathToLogFile): AppConfig
    {
        $this->validateFilePath($pathToLogFile);
        $this->pathToLogFile = $pathToLogFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getPathToContactList(): string
    {
        return $this->pathToContactList;
    }

    /**
     * @param string $pathToContactList
     *
     * @return AppConfig
     */
    public function setPathToContactList(string $pathToContactList): AppConfig
    {
        $this->validateFilePath($pathToContactList);
        $this->pathToContactList = $pathToContactList;
        return $this;
    }




    /**
     * @return string
     */
    public function getPathToAddress(): string
    {
        return $this->pathToAddress;
    }

    /**
     * @param string $pathToAddress
     *
     * @return AppConfig
     */
    public function setPathToAddress(string $pathToAddress): AppConfig
    {
        $this->validateFilePath($pathToAddress);
        $this->pathToAddress = $pathToAddress;
        return $this;
    }



    /**
     * Возвращает путь до файла с получателями
     *
     * @return string
     */
    public function getPathToRecipients(): string
    {
        return $this->pathToRecipients;
    }

    /**
     * Устанавливает путь до файла с получателями
     *
     * @param string $pathToRecipients - путь до файла с получателями
     *
     * @return AppConfig
     */
    private function setPathToRecipients(string $pathToRecipients): AppConfig
    {
        $this->validateFilePath($pathToRecipients);
        $this->pathToRecipients = $pathToRecipients;
        return $this;
    }

    /**
     * Возвращает путь до файла с родственниками
     *
     * @return string
     */
    public function getPathToKinsfolk(): string
    {
        return $this->pathToKinsfolk;
    }

    /**
     * Устанавливает путь до файла с родственниками
     *
     * @param string $pathToKinsfolk - путь до файла с родственниками
     *
     * @return AppConfig
     */
    private function setPathToKinsfolk(string $pathToKinsfolk): AppConfig
    {
        $this->validateFilePath($pathToKinsfolk);
        $this->pathToKinsfolk = $pathToKinsfolk;
        return $this;
    }

    /**
     * Возвращает путь до файла с клиентами
     *
     * @return string
     */
    public function getPathToCustomers(): string
    {
        return $this->pathToCustomers;
    }

    /**
     * Устанавливает путь до файла с клиентами
     *
     * @param string $pathToCustomers - путь до файла с клиентами
     *
     * @return AppConfig
     */
    private function setPathToCustomers(string $pathToCustomers): AppConfig
    {
        $this->validateFilePath($pathToCustomers);
        $this->pathToCustomers = $pathToCustomers;
        return $this;
    }

    /**
     * Возвращает путь до файла с коллегами
     *
     * @return string
     */
    public function getPathToColleagues(): string
    {
        return $this->pathToColleagues;
    }

    /**
     * Устанавливает путь до файла с коллегами
     *
     * @param string $pathToColleagues - путь до файла с коллегами
     *
     * @return AppConfig
     */
    private function setPathToColleagues(string $pathToColleagues): AppConfig
    {
        $this->validateFilePath($pathToColleagues);
        $this->pathToColleagues = $pathToColleagues;
        return $this;
    }

    /**
     * Проверка на корректный путь до файла
     *
     * @param string $path
     *
     * @return void
     * @throws Exception\ErrorCreateAppConfigException
     */
    private function validateFilePath(string $path): void
    {
        if (false === file_exists($path)) {
            throw new Exception\ErrorCreateAppConfigException('Некорректный путь до файла с данными');
        }
    }

    /**
     *  Создает конфиг из массива
     *
     * @param array $config
     *
     * @return static
     * @uses AppConfig::setPathToColleagues()
     * @uses AppConfig::setPathToCustomers()
     * @uses AppConfig::setPathToKinsfolk()
     * @uses AppConfig::setPathToRecipient()
     * @uses AppConfig::setPathToLogFile()
     * @uses AppConfig::setPathToRecipients()
     * @uses AppConfig::setPathToAddress()
     * @uses AppConfig::setLoggerType()
     * @uses AppConfig::setHideErrorMessage()
     */
    public static function createFromArray(array $config): self
    {
        $appConfig = new self();


        foreach ($config as $key => $value) {
            if (property_exists($appConfig, $key)) {
                $setter = 'set' . ucfirst($key);
                $appConfig->{$setter}($value);
            }
        }
        return $appConfig;
    }

}