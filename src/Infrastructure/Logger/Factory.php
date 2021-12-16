<?php

namespace DD\ContactList\Infrastructure\Logger;

use Exception;
use DD\ContactList\Infrastructure\AppConfig;

/**
 *  Фабрика по созданию логеров
 */
class Factory
{
    public function __construct()
    {
    }

    /**
     * Реализация логики создания логгеров
     *
     * @param AppConfig $appConfig
     *
     * @return LoggerInterface
     * @throws Exception
     */
    public static function create(AppConfig $appConfig): LoggerInterface
    {
        if ('fileLogger' === $appConfig->getLoggerType()) {
            $logger = new FileLogger\Logger($appConfig->getPathToLogFile());
        } elseif ('nullLogger' === $appConfig->getLoggerType()) {
            $logger = new NullLogger\Logger();
        } elseif ('echoLogger' === $appConfig->getLoggerType()) {
            $logger = new EchoLogger\Logger();
        } else {
            throw new Exception('Unknown logger type');
        }
        return $logger;
    }

}