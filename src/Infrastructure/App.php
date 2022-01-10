<?php

namespace DD\ContactList\Infrastructure;

use DD\ContactList\Exception;
use DD\ContactList\Infrastructure\DI\ContainerInterface;
use DD\ContactList\Infrastructure\Http\HttpResponse;
use DD\ContactList\Infrastructure\Http\ServerRequest;
use DD\ContactList\Infrastructure\Http\ServerResponseFactory;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;
use DD\ContactList\Infrastructure\Router\RouterInterface;
use DD\ContactList\Infrastructure\View\RenderInterface;
use Throwable;

final class App
{
    /**
     * Компонент отвечающий за логирование
     *
     * @var LoggerInterface|null
     */
    private ?LoggerInterface $logger = null;

    /**
     * Конфиг приложения
     *
     * @var AppConfig|null
     */
    private ?AppConfig $appConfig = null;

    /**
     * Компонент отвечающий за роутинг запросов
     *
     * @var RouterInterface|null
     */
    private ?RouterInterface $router = null;

    /**
     * Компонент отвечающий за рендеринг
     *
     * @var RenderInterface|null
     */
    private ?RenderInterface $render = null;

    /**
     * Локатор сервисов
     *
     * @var ContainerInterface|null
     */
    private ?ContainerInterface $container = null;

    /**
     * Фабрика реализующая логику создания логеров
     *
     * @var callable
     */
    private $loggerFactory;

    /**
     * Фабрика реализующая логику создания конфига приложения
     *
     * @var callable
     */
    private $appConfigFactory;

    /**
     * Фабрика реализующая логику создания рендера
     *
     * @var callable
     */
    private $renderFactory;

    /**
     * Фабрика реализующая логику создания DI контейнера
     *
     * @var callable
     */
    private $diContainerFactory;

    /**
     * Фабрика реализующая роутинг запросов
     *
     * @var callable
     */
    private $routerFactory;


    /**
     * @param callable $routerFactory    - Фабрика реализующая роутинг запросов
     * @param callable $loggerFactory      - Фабрика реализующая логику создания логеров
     * @param callable $appConfigFactory   - Фабрика реализующая логику создания конфига приложения
     * @param callable $renderFactory      - Фабрика реализующая логику создания рендера
     * @param callable $diContainerFactory - Фабрика реализующая логику создания DI контейнера
     */
    public function __construct(
        callable $routerFactory,
        callable $loggerFactory,
        callable $appConfigFactory,
        callable $renderFactory,
        callable $diContainerFactory
    ) {
        $this->routerFactory = $routerFactory;
        $this->loggerFactory = $loggerFactory;
        $this->appConfigFactory = $appConfigFactory;
        $this->renderFactory = $renderFactory;
        $this->diContainerFactory = $diContainerFactory;

        $this->initiateErrorHandling();
    }

    /**
     * Возвращает компонент отвечающий за рендеринг
     *
     * @return RenderInterface
     */
    private function getRender(): RenderInterface
    {
        if (null === $this->render) {
            $this->render = ($this->renderFactory)($this->getContainer());
        }

        return $this->render;
    }

    /**
     * @return RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        if (null === $this->router) {
            $this->router = ($this->routerFactory)($this->getContainer());
        }
        return $this->router;
    }

    /**
     * @return LoggerInterface
     */
    private function getLogger(): LoggerInterface
    {
        if (null === $this->logger) {
            $this->logger = ($this->loggerFactory)($this->getContainer());
        }
        return $this->logger;
    }

    /**
     * @return AppConfig
     */
    private function getAppConfig(): AppConfig
    {
        if (null === $this->appConfig) {
            $this->appConfig = ($this->appConfigFactory)($this->getContainer());
        }
        return $this->appConfig;
    }

    /**
     * @return ContainerInterface
     */
    private function getContainer(): ContainerInterface
    {
        if (null === $this->container) {
            $this->container = ($this->diContainerFactory)();
        }
        return $this->container;
    }


    /**
     * Инициализация обработки ошибок
     *
     * @return void
     */
    private function initiateErrorHandling(): void
    {
        set_error_handler(static function (int $errNom, string $errStr) {
            throw new Exception\RuntimeException($errStr);
        });
    }

    /**
     * Обработчик запроса
     *
     * @param ServerRequest $serverRequest - объект серверного http запроса
     *
     * @return HttpResponse - http ответ
     */
    public
    function dispatch(
        ServerRequest $serverRequest
    ): HttpResponse {
        $hasAppConfig = false;
        try {
            $hasAppConfig = $this->getAppConfig() instanceof AppConfig;

            $logger = $this->getLogger();

            $urlPath = $serverRequest->getUri()->getPath();

            $logger->log('URL request received: ' . $urlPath);

            $dispatcher = $this->getRouter()->getDispatcher($serverRequest);

            if (is_callable($dispatcher)) {
                $httpResponse = $dispatcher($serverRequest);

                if (!$httpResponse instanceof HttpResponse) {
                    throw new Exception\UnexpectedValueException('Контроллер вернул некорректный результат');
                }
            } else {
                $httpResponse = ServerResponseFactory::createJsonResponse(
                    404,
                    ['status' => 'fail', 'message' => 'unsupported request']
                );
            }
            $this->getRender()->render($httpResponse);
        } catch (Exception\InvalidDataStructureException $e) {
            $httpResponse = ServerResponseFactory::createJsonResponse(
                503,
                ['status' => 'fail', 'message' => $e->getMessage()]
            );
            $this->silentRender($httpResponse);
        } catch (Throwable $e) {
            $errMsg = ($hasAppConfig && !$this->getAppConfig()->isHideErrorMessage(
                )) || $e instanceof Exception\ErrorCreateAppConfigException
                ? $e->getMessage()
                : 'system error';

            try {
                $this->logger->log($e->getMessage());
            } catch (Throwable $e1) {
            }
            $this->silentLog($e->getMessage());

            $httpResponse = ServerResponseFactory::createJsonResponse(
                500,
                ['status' => 'fail', 'message' => $errMsg]
            );
            $this->silentRender($httpResponse);
        }

        return $httpResponse;
    }

    /**
     * "Тихое" отображение данных - если отправка данных клиенту закончится ошибкой, то это не приводит к прекращению
     * работы приложения
     *
     * @param HttpResponse $httpResponse
     *
     * @return void
     */
    private function silentRender(HttpResponse $httpResponse): void
    {
        try {
            $this->getRender()->render($httpResponse);
        } catch (Throwable $e) {
            $this->silentLog($e->getMessage());
        }
    }

    /**
     * "Тихое" логирование - если запись сообщения в  лог закончится ошибкой, то это не приведет к падению программы
     *
     * @param string $msg
     *
     * @return void
     */
    private function silentLog(string $msg): void
    {
        try {
            $this->getLogger()->log($msg);
        } catch (Throwable $e) {
        }
    }

}