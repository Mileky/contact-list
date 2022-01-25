<?php

namespace DD\ContactList\Infrastructure\DI;

use DD\ContactList\Infrastructure\Exception;
use Throwable;


class Container implements ContainerInterface
{
    /**
     * Уже созданные сервисы
     *
     * @var array
     */
    private array $instances;

    /**
     * Конфиги для создания сервисов
     *
     * @var array
     */
    private array $services;

    /**
     * Фабрики инкапсулирующие логику создания сервисов
     *
     * @var callable[]
     */
    private array $factories;

    /**
     * @param callable[] $instances
     * @param callable[] $services
     * @param callable[] $factories
     */
    public function __construct(array $instances = [], array $services = [], array $factories = [])
    {
        $this->instances = $instances;
        $this->services = $services;
        $this->factories = $factories;
    }


    /**
     * @inheritDoc
     */
    public function get(string $serviceName)
    {
        if (array_key_exists($serviceName, $this->instances)) {
            $service = $this->instances[$serviceName];
        } elseif (array_key_exists($serviceName, $this->services)) {
            $service = $this->createService($serviceName);
        } elseif (array_key_exists($serviceName, $this->factories)) {
            $service = ($this->factories[$serviceName]($this));
            $this->instances[$serviceName] = $service;
        } else {
            throw new Exception\RuntimeException("Нет данных для создания сервиса" . $serviceName);
        }
        return $service;
    }

    /**
     * Создает контейнер из массива
     *
     * @param array $diConfig
     *
     * @return Container
     */
    public static function createFromArray(array $diConfig): Container
    {
        $instances = array_key_exists('instances', $diConfig) ? $diConfig['instances'] : [];
        $factories = array_key_exists('factories', $diConfig) ? $diConfig['factories'] : [];
        $services = array_key_exists('services', $diConfig) ? $diConfig['services'] : [];

        return new self($instances, $services, $factories);
    }

    /**
     * Создает сервис
     *
     * @param string $serviceName - это имя созданного сервиса
     *
     * @return mixed
     */
    private function createService(string $serviceName)
    {
        $className = $serviceName;
        if (array_key_exists('class', $this->services[$serviceName])) {
            $className = $this->services[$serviceName]['class'];
        }

        if (false === is_string($className)) {
            throw new Exception\DomainException(
                'Имя создаваемого класса должно быть строкой'
            );
        }

        $args = [];
        if (array_key_exists('args', $this->services[$serviceName])) {
            $args = $this->services[$serviceName]['args'];
        }

        if (false === is_array($args)) {
            throw new Exception\DomainException(
                'Аргументы должны быть определены массивом'
            );
        }

        $resolvedArgs = [];
        foreach ($args as $arg) {
            $resolvedArgs[] = $this->get($arg);
        }
        try {
            $instance = new $className(...$resolvedArgs);
        } catch (Throwable $e) {
            throw new Exception\RuntimeException("Ошибка в создании сервиса: " . $serviceName);
        }
        $this->instances['serviceName'] = $instance;


        return $instance;
    }

}