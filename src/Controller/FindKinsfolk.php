<?php

namespace DD\ContactList\Controller;

use DD\ContactList\Entity\Kinsfolk;
use DD\ContactList\Entity\Recipient;
use DD\ContactList\Infrastructure\AppConfig;
use DD\ContactList\Infrastructure\Controller\ControllerInterface;
use DD\ContactList\Infrastructure\DataLoader\JsonDataLoader;
use DD\ContactList\Infrastructure\DI\ContainerInterface;
use DD\ContactList\Infrastructure\DI\ServiceLocator;
use DD\ContactList\Infrastructure\Http\HttpResponse;
use DD\ContactList\Infrastructure\Http\ServerRequest;
use DD\ContactList\Infrastructure\Http\ServerResponseFactory;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;
use DD\ContactList\Infrastructure\Validator\Assert;
use JsonException;

final class FindKinsfolk implements ControllerInterface
{
    /**
     * Логгер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Конфиг приложения
     *
     * @var AppConfig
     */
    private AppConfig $appConfig;

    /**
     * @param AppConfig $appConfig
     * @param LoggerInterface $logger
     */
    public function __construct(AppConfig $appConfig, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->appConfig = $appConfig;

    }

    /**
     * Валидирует параметры запроса
     *
     * @param ServerRequest $serverRequest - объект серверного запроса
     *
     * @return string|null - строка с ошибкой или null, если ошибки нет
     */
    private function validateQueryParams(ServerRequest $serverRequest): ?string
    {
        $paramValidations = [
            'id_recipient' => 'incorrect id_recipient',
            'full_name' => 'incorrect full_name',
            'birthday' => 'incorrect birthday',
            'profession' => 'incorrect profession',
            'status' => 'incorrect status',
            'ringtone' => 'incorrect ringtone',
            'hotkey' => 'incorrect hotkey'
        ];

        $queryParams = $serverRequest->getQueryParams();

        return Assert::arrayElementsIsString($paramValidations, $queryParams);
    }

    /**
     * Логика поиска Родственников
     *
     * @param array $kinsfolk - массив с данными из файла с Родственниками
     * @param ServerRequest $serverRequest
     *
     * @return Recipient[]
     */
    private function searchKinsfolkInData(array $kinsfolk, ServerRequest $serverRequest): array
    {
        $foundKinsfolk = [];
        $requestParams = $serverRequest->getQueryParams();
        foreach ($kinsfolk as $relative) {
            if (array_key_exists('id_recipient', $requestParams)) {
                $kinsfolkMeetSearchCriteria = $requestParams['id_recipient'] === (string)$relative['id_recipient'];
            } elseif (array_key_exists('full_name', $requestParams)) {
                $kinsfolkMeetSearchCriteria = $requestParams['full_name'] === $relative['full_name'];
            } elseif (array_key_exists('birthday', $requestParams)) {
                $kinsfolkMeetSearchCriteria = $requestParams['birthday'] === $relative['birthday'];
            } elseif (array_key_exists('profession', $requestParams)) {
                $kinsfolkMeetSearchCriteria = $requestParams['profession'] === $relative['profession'];
            } elseif (array_key_exists('status', $requestParams)) {
                $kinsfolkMeetSearchCriteria = $requestParams['status'] === $relative['status'];
            } elseif (array_key_exists('ringtone', $requestParams)) {
                $kinsfolkMeetSearchCriteria = $requestParams['ringtone'] === $relative['ringtone'];
            } elseif (array_key_exists('hotkey', $requestParams)) {
                $kinsfolkMeetSearchCriteria = $requestParams['hotkey'] === $relative['hotkey'];
            } else {
                $kinsfolkMeetSearchCriteria = true;
            }
            if ($kinsfolkMeetSearchCriteria) {
                $foundKinsfolk[] = Kinsfolk::createFromArray($relative);
            }
        }
        $this->logger->log('found kinsfolk: ' . count($foundKinsfolk));

        return $foundKinsfolk;
    }


    /**
     * Загрузка данных о Родственниках
     *
     * @throws JsonException
     */
    private function loadData(): array
    {
        return (new JsonDataLoader())->loadData($this->appConfig->getPathToKinsfolk());
    }

    /**
     * обработка запроса поиска Родственников
     *
     * @param ServerRequest $serverRequest
     *
     * @return HttpResponse
     * @throws JsonException
     */
    public function __invoke(ServerRequest $serverRequest): HttpResponse
    {
        $this->logger->log('dispatch "kinsfolk" url');


        $resultOfParamValidation = $this->validateQueryParams($serverRequest);


        if (null === $resultOfParamValidation) {
            $kinsfolk = $this->loadData();
            $httpCode = 200;
            $result = $this->searchKinsfolkInData($kinsfolk, $serverRequest);
        } else {
            $httpCode = 500;
            $result = [
                'status' => 'fail',
                'message' => $resultOfParamValidation
            ];
        }
        return ServerResponseFactory::createJsonResponse($httpCode, $result);
    }
}