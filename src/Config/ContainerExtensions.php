<?php

namespace DD\ContactList\Config;

use DD\ContactList\Infrastructure\Logger\SymfonyDi\DiLoggerExt;
use DD\ContactList\Infrastructure\Router\SymfonyDi\DiRouterExt;
use DD\ContactList\Infrastructure\ViewTemplate\SymfonyDi\DiViewTemplateExt;

/**
 * Наборы расширений для di контейнера
 */
class ContainerExtensions
{
    /**
     * Возвращает коллекцию расширений di контейнера symfony для работы http приложения
     *
     * @return array
     */
    public static function httpAppContainerExtension(): array
    {
        return [
            new DiRouterExt(),
            new DiLoggerExt(),
            new DiViewTemplateExt()
        ];
    }

    /**
     * Возвращает коллекцию расширений di контейнера symfony для работы консольного приложения
     *
     * @return array
     */
    public static function consoleContainerExtension(): array
    {
        return [
            new DiRouterExt(),
            new DiLoggerExt()
        ];
    }
}