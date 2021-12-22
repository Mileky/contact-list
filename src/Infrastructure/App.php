<?php

namespace DD\ContactList\Infrastructure;

use DD\ContactList\Exception;
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
     * @param array $handler - обработчики запросов
     * @param callable $loggerFactory - фабрика создания логгеров
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
     * Извлекает параметры из URL
     *
     * @param string $requestUri - данные запроса URI
     *
     * @return array - параметры запроса
     */
    private function extractQueryParams(string $requestUri): array
    {
        $query = parse_url($requestUri, PHP_URL_QUERY);
        $requestParams = [];
        parse_str($query, $requestParams);

        return $requestParams;
    }


    public function dispatch(string $requestUri): array
    {
        $appConfig = null;
        try {
            $appConfig = $this->getAppConfig();

            $logger = $this->getLogger();

            $urlPath = parse_url($requestUri, PHP_URL_PATH);

            $logger->log('URL request received: ' . $requestUri);

            if (array_key_exists($urlPath, $this->handlers)) {
                $requestParams = $this->extractQueryParams($requestUri);
                $result = call_user_func($this->handlers[$urlPath], $requestParams, $logger, $appConfig);
            } else {
                $result = [
                    'httpCode' => 404,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'unsupported request'
                    ]
                ];
            }

        } catch (Exception\InvalidDataStructureException $e) {
            $result = [
                'httpCode' => 503,
                'result' => [
                    'status' => 'fail',
                    'message' => $e->getMessage()
                ]
            ];
        } catch (Throwable $e) {
            $errMsg = ($appConfig instanceof AppConfig && !$appConfig->isHideErrorMessage()) || $e instanceof Exception\ErrorCreateAppConfigException
                ? $e->getMessage()
                : 'system error';

            try {
                $this->getLogger()->log($e->getMessage());
            } catch (Throwable $e1) {
            }

            $result = [
                'httpCode' => 500,
                'result' => [
                    'status' => 'fail',
                    'message' => $errMsg
                ]
            ];
        }

        return $result;
    }
}