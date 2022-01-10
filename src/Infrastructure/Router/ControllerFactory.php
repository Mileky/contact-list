<?php

namespace DD\ContactList\Infrastructure\Router;

use DD\ContactList\Infrastructure\Controller\ControllerInterface;
use DD\ContactList\Infrastructure\DI\ContainerInterface;

/**
 * Фабрика создания контроллеров
 */
class ControllerFactory
{
    /**
     * di контейнер
     *
     * @var ContainerInterface
     */
    private ContainerInterface $diContainer;

    /**
     * @param ContainerInterface $diContainer
     */
    public function __construct(ContainerInterface $diContainer)
    {
        $this->diContainer = $diContainer;
    }

    /**
     * Создает контроллер
     *
     * @param string $controllerClassName - имя класса, создаваемого контроллера
     *
     * @return ControllerInterface
     */
    public function create(string $controllerClassName): ControllerInterface
    {
        return $this->diContainer->get($controllerClassName);
    }

}