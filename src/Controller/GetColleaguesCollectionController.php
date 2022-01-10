<?php

namespace DD\ContactList\Controller;

use DD\ContactList\Entity\Colleague;
use DD\ContactList\Infrastructure\Controller\ControllerInterface;
use DD\ContactList\Infrastructure\DataLoader\JsonDataLoader;
use DD\ContactList\Infrastructure\Http\HttpResponse;
use DD\ContactList\Infrastructure\Http\ServerRequest;
use DD\ContactList\Infrastructure\Http\ServerResponseFactory;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;
use DD\ContactList\Infrastructure\Validator\Assert;
use JsonException;

/**
 * Контроллер для работы с Коллегами
 */
class GetColleaguesCollectionController implements ControllerInterface
{
    /**
     * Путь до файла с коллегами
     *
     * @var string
     */
    private string $pathToColleagues;

    /**
     * Логгер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger
     * @param string $pathToColleagues
     */
    public function __construct(LoggerInterface $logger, string $pathToColleagues)
    {
        $this->logger = $logger;
        $this->pathToColleagues = $pathToColleagues;
    }

    /**
     * Определяет http код
     *
     * @param array $foundColleagues
     *
     * @return int
     */
    protected function buildHttpCode(array $foundColleagues): int
    {
        return 200;
    }

    /**
     * Подготавливает данные для ответа
     *
     * @param array $foundColleagues
     *
     * @return array
     */
    protected function buildResult(array $foundColleagues)
    {
        return $foundColleagues;
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

        $params = array_merge($serverRequest->getQueryParams(), $serverRequest->getAttributes());

        return Assert::arrayElementsIsString($paramValidations, $params);
    }

    /**
     * Логика поиска Коллег
     *
     * @param array $colleagues - массив с данными из файла с Коллегами
     * @param ServerRequest $serverRequest
     *
     * @return Colleague[]
     */
    private function searchColleaguesInData(array $colleagues, ServerRequest $serverRequest): array
    {
        $foundColleagues = [];
        $searchCriteria = array_merge($serverRequest->getQueryParams(), $serverRequest->getAttributes());
        foreach ($colleagues as $colleague) {
            if (array_key_exists('id_recipient', $searchCriteria)) {
                $colleaguesMeetSearchCriteria = $searchCriteria['id_recipient'] === $colleague['id_recipient'];
            } elseif (array_key_exists('full_name', $searchCriteria)) {
                $colleaguesMeetSearchCriteria = $searchCriteria['full_name'] === $colleague['full_name'];
            } elseif (array_key_exists('birthday', $searchCriteria)) {
                $colleaguesMeetSearchCriteria = $searchCriteria['birthday'] === $colleague['birthday'];
            } elseif (array_key_exists('profession', $searchCriteria)) {
                $colleaguesMeetSearchCriteria = $searchCriteria['profession'] === $colleague['profession'];
            } elseif (array_key_exists('department', $searchCriteria)) {
                $colleaguesMeetSearchCriteria = $searchCriteria['department'] === $colleague['department'];
            } elseif (array_key_exists('position', $searchCriteria)) {
                $colleaguesMeetSearchCriteria = $searchCriteria['position'] === $colleague['position'];
            } elseif (array_key_exists('room_number', $searchCriteria)) {
                $colleaguesMeetSearchCriteria = $searchCriteria['room_number'] === $colleague['room_number'];
            } else {
                $colleaguesMeetSearchCriteria = true;
            }
            if ($colleaguesMeetSearchCriteria) {
                $foundColleagues[] = Colleague::createFromArray($colleague);
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
        return (new JsonDataLoader())->loadData($this->pathToColleagues);
    }

    /**
     * обработка запроса поиска Коллег
     *
     * @param ServerRequest $serverServerRequest
     *
     * @return HttpResponse
     * @throws JsonException
     */
    public function __invoke(ServerRequest $serverServerRequest): HttpResponse
    {
        $this->logger->log('dispatch "colleagues" url');


        $resultOfParamValidation = $this->validateQueryParams($serverServerRequest);


        if (null === $resultOfParamValidation) {
            $colleagues = $this->loadData();
            $foundColleagues = $this->searchColleaguesInData($colleagues, $serverServerRequest);
            $httpCode = $this->buildHttpCode($foundColleagues);
            $result = $this->buildResult($foundColleagues);
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