<?php

namespace DD\ContactList\Infrastructure\DI;

use DD\ContactList\Exception;

/**
 * Сервис менеджер
 */
class ServiceManager implements ContainerInterface
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
     * Фабррики для создания сервисов
     *
     * @var callable[]
     */
    private array $factories;

    /**
     * @param array $instances
     * @param callable[] $factories
     */
    public function __construct(array $instances = [], array $factories = [])
    {
        $this->instances = $instances;
        $this->registerFactories(...$factories);
    }

    /**
     * Регистрация фабрик
     *
     * @param callable ...$factories
     *
     * @return void
     */
    private function registerFactories(callable ...$factories): void
    {
        $this->factories = $factories;
    }


    /**
     * @inheritDoc
     */
    public function get(string $serviceName)
    {
        if (array_key_exists($serviceName, $this->instances)) {
            $service = $this->instances[$serviceName];
        } elseif (array_key_exists($serviceName, $this->factories)) {
            $service = ($this->factories[$serviceName])($this);
            $this->instances[$serviceName] = $service;
        } else {
            throw new Exception\RuntimeException('Не удалось создать сервис ' . $serviceName);
        }

        return $service;
    }
}