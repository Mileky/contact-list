<?php

namespace DD\ContactList\Infrastructure\Router;

use DD\ContactList\Infrastructure\Controller\ControllerInterface;
use DD\ContactList\Infrastructure\Http\ServerRequest;

class DefaultRouter implements RouterInterface
{
    /**
     * Ассоциативный массив, в котором сопоставляются url path и обработчики
     *
     * @var array
     */
    private array $handlers;

    /**
     * di контейнер
     *
     * @var ControllerFactory
     */
    private ControllerFactory $controllerFactory;

    /**
     * @param array $handlers
     * @param ControllerFactory $controllerFactory
     */
    public function __construct(array $handlers, ControllerFactory $controllerFactory)
    {
        $this->handlers = $handlers;
        $this->controllerFactory = $controllerFactory;
    }

    /**
     * @inheritDoc
     */
    public function getDispatcher(ServerRequest $serverRequest): ?callable
    {
        $urlPath = $serverRequest->getUri()->getPath();
        $dispatcher = null;

        if (array_key_exists($urlPath, $this->handlers)) {
            if (is_callable($this->handlers[$urlPath])) {
                $dispatcher = $this->handlers[$urlPath];
            } elseif (is_string($this->handlers[$urlPath]) && is_subclass_of(
                    $this->handlers[$urlPath],
                    ControllerInterface::class,
                    true
                )) {
                $dispatcher = $this->controllerFactory->create($this->handlers[$urlPath]);
            }
        }
        return $dispatcher;
    }

}