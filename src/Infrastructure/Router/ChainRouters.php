<?php

namespace DD\ContactList\Infrastructure\Router;

use DD\ContactList\Infrastructure\Http\ServerRequest;

/**
 * Цепочка роутеров
 */
class ChainRouters implements RouterInterface
{
    /**
     * Цепочка роутеров
     *
     * @var RouterInterface[]
     */
    private array $routers;

    /**
     * @param RouterInterface[] $routers
     */
    public function __construct(RouterInterface ...$routers)
    {
        $this->routers = $routers;
    }

    /**
     * @inheritDoc
     */
    public function getDispatcher(ServerRequest $serverRequest): ?callable
    {
        $dispatcher = null;

        foreach ($this->routers as $router) {
            $currentDispatcher = $router->getDispatcher($serverRequest);
            if (is_callable($currentDispatcher)) {
                $dispatcher = $currentDispatcher;
                break;
            }
        }

        return $dispatcher;
    }
}