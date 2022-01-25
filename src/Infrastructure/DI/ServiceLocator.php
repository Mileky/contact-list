<?php

namespace DD\ContactList\Infrastructure\DI;

use DD\ContactList\Infrastructure\Exception;

/**
 * Локатор сервисов
 */
class ServiceLocator implements ContainerInterface
{
    /**
     * Инстансы зарегестрированных сервисов
     * - ключ это имя сервиса (совпадает  именем класса или интерфейса)
     * - значение сам сервис (обычно объект)
     *
     *
     * @var array
     */
    private array $instances;

    /**
     * @param array $instances
     */
    public function __construct(array $instances)
    {
        $this->instances = $instances;
    }

    /**
     * Возвращает инстанс сервиса по заданному имени
     *
     * @param string $serviceName
     *
     * @return mixed
     */
    public function get(string $serviceName)
    {
        if (false === array_key_exists($serviceName, $this->instances)) {
            throw new Exception\RuntimeException('Отсутствует сервис с именем ' . $serviceName);
        }

        return $this->instances[$serviceName];
    }


}