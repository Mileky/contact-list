<?php

namespace DD\ContactList\Controller;

use DD\ContactList\Entity\Kinsfolk;
use DD\ContactList\Infrastructure\Controller\ControllerInterface;
use DD\ContactList\Infrastructure\DataLoader\JsonDataLoader;
use DD\ContactList\Infrastructure\Http\HttpResponse;
use DD\ContactList\Infrastructure\Http\ServerRequest;
use DD\ContactList\Infrastructure\Http\ServerResponseFactory;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;
use DD\ContactList\Infrastructure\Validator\Assert;
use JsonException;

/**
 * Контроллер для работы с Родственниками
 */
class GetKinsfolkCollectionController implements ControllerInterface
{

    /**
     * Путь до файла с Родственниками
     *
     * @var string
     */
    private string $pathToKinsfolk;

    /**
     * Логгер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger
     * @param string $pathToKinsfolk
     */
    public function __construct(LoggerInterface $logger, string $pathToKinsfolk)
    {
        $this->logger = $logger;
        $this->pathToKinsfolk = $pathToKinsfolk;

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

        $params = array_merge($serverRequest->getQueryParams(), $serverRequest->getAttributes());

        return Assert::arrayElementsIsString($paramValidations, $params);
    }

    /**
     * Определяет http код
     *
     * @param array $foundKinsfolk
     *
     * @return int
     */
    protected function buildHttpCode(array $foundKinsfolk): int
    {
        return 200;
    }

    /**
     * Подготавливает данные для ответа
     *
     * @param array $foundKinsfolk
     *
     * @return array
     */
    protected function buildResult(array $foundKinsfolk)
    {
        return $foundKinsfolk;
    }

    /**
     * Логика поиска Родственников
     *
     * @param array $kinsfolk - массив с данными из файла с Родственниками
     * @param ServerRequest $serverRequest
     *
     * @return Kinsfolk[]
     */
    private function searchKinsfolkInData(array $kinsfolk, ServerRequest $serverRequest): array
    {
        $foundKinsfolk = [];
        $searchCriteria = array_merge($serverRequest->getQueryParams(), $serverRequest->getAttributes());
        foreach ($kinsfolk as $relative) {
            if (array_key_exists('id_recipient', $searchCriteria)) {
                $kinsfolkMeetSearchCriteria = $searchCriteria['id_recipient'] === (string)$relative['id_recipient'];
            } elseif (array_key_exists('full_name', $searchCriteria)) {
                $kinsfolkMeetSearchCriteria = $searchCriteria['full_name'] === $relative['full_name'];
            } elseif (array_key_exists('birthday', $searchCriteria)) {
                $kinsfolkMeetSearchCriteria = $searchCriteria['birthday'] === $relative['birthday'];
            } elseif (array_key_exists('profession', $searchCriteria)) {
                $kinsfolkMeetSearchCriteria = $searchCriteria['profession'] === $relative['profession'];
            } elseif (array_key_exists('status', $searchCriteria)) {
                $kinsfolkMeetSearchCriteria = $searchCriteria['status'] === $relative['status'];
            } elseif (array_key_exists('ringtone', $searchCriteria)) {
                $kinsfolkMeetSearchCriteria = $searchCriteria['ringtone'] === $relative['ringtone'];
            } elseif (array_key_exists('hotkey', $searchCriteria)) {
                $kinsfolkMeetSearchCriteria = $searchCriteria['hotkey'] === $relative['hotkey'];
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
        return (new JsonDataLoader())->loadData($this->pathToKinsfolk);
    }

    /**
     * обработка запроса поиска Родственников
     *
     * @param ServerRequest $serverServerRequest
     *
     * @return HttpResponse
     * @throws JsonException
     */
    public function __invoke(ServerRequest $serverServerRequest): HttpResponse
    {
        $this->logger->log('dispatch "kinsfolk" url');


        $resultOfParamValidation = $this->validateQueryParams($serverServerRequest);


        if (null === $resultOfParamValidation) {
            $kinsfolk = $this->loadData();
            $foundKinsfolk = $this->searchKinsfolkInData($kinsfolk, $serverServerRequest);
            $httpCode = $this->buildHttpCode($foundKinsfolk);
            $result = $this->buildResult($foundKinsfolk);
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