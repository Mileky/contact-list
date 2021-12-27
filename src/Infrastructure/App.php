<?php

namespace DD\ContactList\Infrastructure;

use DD\ContactList\Exception;
use DD\ContactList\Infrastructure\Http\HttpResponse;
use DD\ContactList\Infrastructure\Http\ServerRequest;
use DD\ContactList\Infrastructure\Http\ServerResponseFactory;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;
use Throwable;
use UnexpectedValueException;

final class App
{
    /**
     * Обработчик запросов
     *
     * @var array
     */
    private array $handlers;

    /**
     * Фабрика создания логгеров
     *
     * @var callable
     */
    private $loggerFactory;

    /**
     * Компонент отвечающий за логирование
     *
     * @var LoggerInterface|null
     */
    private ?LoggerInterface $logger = null;

    /**
     * Фабрика создания конфига приложения
     *
     * @var callable
     */
    private $appConfigFactory;

    /**
     * Конфиг приложения
     *
     * @var AppConfig|null
     */
    private ?AppConfig $appConfig = null;

    /**
     * @param array $handler             - обработчики запросов
     * @param callable $loggerFactory    - фабрика создания логгеров
     * @param callable $appConfigFactory - фабрика создания конфига приложения
     */
    public function __construct(array $handler, callable $loggerFactory, callable $appConfigFactory)
    {
        $this->handlers = $handler;
        $this->loggerFactory = $loggerFactory;
        $this->appConfigFactory = $appConfigFactory;
        $this->initiateErrorHandling();
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
     * Возвращает конфиг приложения
     *
     * @return AppConfig
     */
    private function getAppConfig(): AppConfig
    {
        if (null === $this->appConfig) {
            try {
                $appConfig = call_user_func($this->appConfigFactory);
            } catch (Throwable $e) {
                throw new Exception\ErrorCreateAppConfigException($e->getMessage(), $e->getCode(), $e);
            }

            if (!($appConfig instanceof AppConfig)) {
                throw new Exception\ErrorCreateAppConfigException('Incorrect application config');
            }
            $this->appConfig = $appConfig;
        }
        return $this->appConfig;
    }

    /**
     * Возвращает логгер
     *
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        if (null === $this->logger) {
            $logger = call_user_func($this->loggerFactory, $this->getAppConfig());
            if (!($logger instanceof LoggerInterface)) {
                throw new UnexpectedValueException('Incorrect logger');
            }
            $this->logger = $logger;
        }
        return $this->logger;
    }

    /**
     * Обработчик запроса
     *
     * @param ServerRequest $serverRequest - объект серверного http запроса
     *
     * @return HttpResponse - http ответ
     */
    public function dispatch(ServerRequest $serverRequest): HttpResponse
    {
        $appConfig = null;
        try {
            $appConfig = $this->getAppConfig();

            $logger = $this->getLogger();

            $urlPath = $serverRequest->getUri()->getPath();

            $logger->log('URL request received: ' . $urlPath);

            if (array_key_exists($urlPath, $this->handlers)) {
                $httpResponse = call_user_func($this->handlers[$urlPath], $serverRequest, $logger, $appConfig);
            } else {
                $httpResponse = ServerResponseFactory::createJsonResponse(
                    404,
                    ['status' => 'fail', 'message' => 'unsupported request']
                );
            }
        } catch (Exception\InvalidDataStructureException $e) {
            $httpResponse = ServerResponseFactory::createJsonResponse(
                503,
                ['status' => 'fail', 'message' => $e->getMessage()]
            );
        } catch (Throwable $e) {
            $errMsg = ($appConfig instanceof AppConfig && !$appConfig->isHideErrorMessage(
                )) || $e instanceof Exception\ErrorCreateAppConfigException
                ? $e->getMessage()
                : 'system error';

            try {
                $this->getLogger()->log($e->getMessage());
            } catch (Throwable $e1) {
            }

            $httpResponse = ServerResponseFactory::createJsonResponse(
                500,
                ['status' => 'fail', 'message' => $errMsg]
            );
        }

        return $httpResponse;
    }
}