<?php

namespace DD\ContactList\Infrastructure\DI;


/**
 * Интерфейс контейнеров используемых для внедрения зависимостей
 */
interface ContainerInterface
{
    /**
     * Возвращает инстанс сервиса по заданному имени
     *
     * @param string $serviceName
     *
     * @return mixed
     */
    public function get(string $serviceName);
}