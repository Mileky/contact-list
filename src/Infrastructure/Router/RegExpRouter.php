<?php

namespace DD\ContactList\Infrastructure\Router;

use DD\ContactList\Infrastructure\Controller\ControllerInterface;
use DD\ContactList\Infrastructure\Http\ServerRequest;

/**
 * Роутер сопоставляющий регулярные выражения и обработчик
 */
class RegExpRouter implements RouterInterface
{

    /**
     * Ассоциативный массив. Ключ - регулярное выражение, а значение - обработчик
     *
     * @var array
     */
    private array $handlers;

    /**
     * Фабрика по созданию контроллеров
     *
     * @var ControllerFactory
     */
    private ControllerFactory $controllerFactory;

    /**
     * @param array             $handlers          - Ассоциативный массив. Ключ - регулярное выражение, а значение -
     *                                             обработчик
     * @param ControllerFactory $controllerFactory - Фабрика по созданию контроллеров
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

        foreach ($this->handlers as $pattern => $currentDispatcher) {
            $matches = [];
            if (1 === preg_match($pattern, $urlPath, $matches)) {
                if (is_callable($currentDispatcher)) {
                    $dispatcher = $currentDispatcher;
                } elseif (is_string($currentDispatcher) && is_subclass_of(
                        $currentDispatcher,
                        ControllerInterface::class,
                        true
                    )) {
                    $dispatcher = $this->controllerFactory->create($currentDispatcher);
                }
            }

            if (null !== $dispatcher) {
                $serverRequestAttributes = $this->buildServerRequestAttributes($matches);
                $serverRequest->setAttributes($serverRequestAttributes);
                break;
            }
        }

        return $dispatcher;
    }

    /**
     * Получение атрибутов серверного запроса
     *
     * @param array $matches
     *
     * @return array
     */
    private function buildServerRequestAttributes(array $matches): array
    {
        $attributes = [];

        foreach ($matches as $key => $value) {
            if (0 === strpos($key, '___') && '___' === substr($key, -3) && strlen($key) > 6) {
                $attributes[$this->buildAttrName($key)] = $value;
            }
        }

        return $attributes;
    }

    /**
     * Получить имя атрибута
     *
     * @param string $groupName
     *
     * @return string
     */
    private function buildAttrName(string $groupName): string
    {
        $clearAttrName = strtolower(substr($groupName, 3, -3));

        $parts = explode('_', $clearAttrName);

        $ucParts = array_map('ucfirst', $parts);

        return lcfirst(implode('', $ucParts));

    }
}