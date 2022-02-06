<?php

namespace DD\ContactList\Config;

use DD\ContactList\Exception;
use DD\ContactList\Infrastructure\HttpApplication\AppConfig as BaseConfig;

/**
 *  Конфиг приложения
 */
class AppConfig extends BaseConfig
{
    /**
     * Путь до файла с данными о знакомых
     *
     * @var string
     */
    private string $pathToRecipients;
    /**
     * Путь до файла с данными о родственниках
     *
     * @var string
     */
    private string $pathToKinsfolk;
    /**
     * Путь до файла с данными о клиентах
     *
     * @var string
     */
    private string $pathToCustomers;
    /**
     * Путь до файла с данными о коллегах
     *
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
     * Путь до файла с данными о пользователях
     *
     * @var string
     */
    private string $pathToUsers;

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
     * Uri формы авторизации
     *
     * @var string
     */
    private string $loginUri;

    /**
     * @return string
     */
    public function getPathToUsers(): string
    {
        return $this->pathToUsers;
    }

    /**
     * @param string $pathToUsers
     *
     * @return AppConfig
     */
    protected function setPathToUsers(string $pathToUsers): AppConfig
    {
        $this->validateFilePath($pathToUsers);
        $this->pathToUsers = $pathToUsers;
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
     * Возвращает uri формы аутентификации
     *
     * @return string
     */
    public function getLoginUri(): string
    {
        return $this->loginUri;
    }

    /**
     * Устанавливает uri формы аутентификации
     *
     * @param string $loginUri
     *
     * @return AppConfig
     */
    protected function setLoginUri(string $loginUri): AppConfig
    {
        $this->loginUri = $loginUri;
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
    protected function setPathToLogFile(string $pathToLogFile): AppConfig
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
    protected function setPathToContactList(string $pathToContactList): AppConfig
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
    protected function setPathToAddress(string $pathToAddress): AppConfig
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
    protected function setPathToRecipients(string $pathToRecipients): AppConfig
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
    protected function setPathToKinsfolk(string $pathToKinsfolk): AppConfig
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
    protected function setPathToCustomers(string $pathToCustomers): AppConfig
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
    protected function setPathToColleagues(string $pathToColleagues): AppConfig
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
}
