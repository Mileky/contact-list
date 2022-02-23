<?php

namespace DD\ContactList\Config;

use DD\ContactList\Infrastructure\Db\SymfonyDi\DiDbExt;
use DD\ContactList\Infrastructure\Http\SymfonyDi\DiHttpExt;
use DD\ContactList\Infrastructure\Router\SymfonyDi\DiRouterExt;

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
            new DiHttpExt(),
            new DiDbExt()
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
            new DiDbExt(),
            new DiHttpExt()
        ];
    }
}
