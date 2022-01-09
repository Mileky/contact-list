<?php

namespace DD\ContactList\Controller;

use DD\ContactList\Entity\Recipient;
use DD\ContactList\Infrastructure\AppConfig;
use DD\ContactList\Infrastructure\Controller\ControllerInterface;
use DD\ContactList\Infrastructure\DataLoader\JsonDataLoader;
use DD\ContactList\Infrastructure\Http\HttpResponse;
use DD\ContactList\Infrastructure\Http\ServerRequest;
use DD\ContactList\Infrastructure\Http\ServerResponseFactory;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;
use DD\ContactList\Infrastructure\Validator\Assert;
use JsonException;

class FindColleagues implements ControllerInterface
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
     * @param LoggerInterface $logger
     * @param AppConfig $appConfig
     */
    public function __construct(LoggerInterface $logger, AppConfig $appConfig)
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
            'department' => 'incorrect department',
            'position' => 'incorrect position',
            'room_number' => 'incorrect room_number',
        ];

        $queryParams = $serverRequest->getQueryParams();

        return Assert::arrayElementsIsString($paramValidations, $queryParams);
    }

    /**
     * Логика поиска Коллег
     *
     * @param array $colleagues - массив с данными из файла с Коллегами
     * @param ServerRequest $serverRequest
     *
     * @return Recipient[]
     */
    private function searchColleaguesInData(array $colleagues, ServerRequest $serverRequest): array
    {
        $foundColleagues = [];
        $requestParams = $serverRequest->getQueryParams();
        foreach ($colleagues as $colleague) {
            if (array_key_exists('id_recipient', $requestParams)) {
                $colleaguesMeetSearchCriteria = $requestParams['id_recipient'] === $colleague['id_recipient'];
            } elseif (array_key_exists('full_name', $requestParams)) {
                $colleaguesMeetSearchCriteria = $requestParams['full_name'] === $colleague['full_name'];
            } elseif (array_key_exists('birthday', $requestParams)) {
                $colleaguesMeetSearchCriteria = $requestParams['birthday'] === $colleague['birthday'];
            } elseif (array_key_exists('profession', $requestParams)) {
                $colleaguesMeetSearchCriteria = $requestParams['profession'] === $colleague['profession'];
            } elseif (array_key_exists('department', $requestParams)) {
                $colleaguesMeetSearchCriteria = $requestParams['department'] === $colleague['department'];
            } elseif (array_key_exists('position', $requestParams)) {
                $colleaguesMeetSearchCriteria = $requestParams['position'] === $colleague['position'];
            } elseif (array_key_exists('room_number', $requestParams)) {
                $colleaguesMeetSearchCriteria = $requestParams['room_number'] === $colleague['room_number'];
            } else {
                $colleaguesMeetSearchCriteria = true;
            }
            if ($colleaguesMeetSearchCriteria) {
                $foundColleagues[] = Recipient::createFromArray($colleague);
            }
        }
        $this->logger->log('found recipients: ' . count($foundColleagues));

        return $foundColleagues;
    }


    /**
     * Загрузка данных о Коллегах
     *
     * @throws JsonException
     */
    private function loadData(): array
    {
        return (new JsonDataLoader())->loadData($this->appConfig->getPathToColleagues());
    }

    /**
     * обработка запроса поиска Коллег
     *
     * @param ServerRequest $serverRequest
     *
     * @return HttpResponse
     * @throws JsonException
     */
    public function __invoke(ServerRequest $serverRequest): HttpResponse
    {
        $this->logger->log('dispatch "colleagues" url');


        $resultOfParamValidation = $this->validateQueryParams($serverRequest);


        if (null === $resultOfParamValidation) {
            $colleagues = $this->loadData();
            $httpCode = 200;
            $result = $this->searchColleaguesInData($colleagues, $serverRequest);
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