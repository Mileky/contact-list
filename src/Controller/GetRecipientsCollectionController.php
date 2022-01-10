<?php

namespace DD\ContactList\Controller;

use DD\ContactList\Entity\Recipient;
use DD\ContactList\Infrastructure\Controller\ControllerInterface;
use DD\ContactList\Infrastructure\DataLoader\JsonDataLoader;
use DD\ContactList\Infrastructure\Http\HttpResponse;
use DD\ContactList\Infrastructure\Http\ServerRequest;
use DD\ContactList\Infrastructure\Http\ServerResponseFactory;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;
use DD\ContactList\Infrastructure\Validator\Assert;
use JsonException;

/**
 * Контроллер для работы с Знакомыми
 */
class GetRecipientsCollectionController implements ControllerInterface
{

    /**
     * Путь до файла с Знакомыми
     *
     * @var string
     */
    private string $pathToRecipients;

    /**
     * Логгер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger
     * @param string $pathToRecipients
     */
    public function __construct(LoggerInterface $logger, string $pathToRecipients)
    {
        $this->logger = $logger;
        $this->pathToRecipients = $pathToRecipients;

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
            'profession' => 'incorrect profession'
        ];

        $queryParams = $serverRequest->getQueryParams();

        return Assert::arrayElementsIsString($paramValidations, $queryParams);
    }

    /**
     * Определяет http код
     *
     * @param array $foundRecipients
     *
     * @return int
     */
    protected function buildHttpCode(array $foundRecipients): int
    {
        return 200;
    }

    /**
     * Подготавливает данные для ответа
     *
     * @param array $foundRecipients
     *
     * @return array
     */
    protected function buildResult(array $foundRecipients)
    {
        return $foundRecipients;
    }


    /**
     * Логика поиска Знакомых
     *
     * @param array $recipients
     * @param ServerRequest $serverRequest
     *
     * @return Recipient[]
     */
    private function searchRecipientInData(array $recipients, ServerRequest $serverRequest): array
    {
        $foundRecipients = [];
        $searchCriteria = array_merge($serverRequest->getQueryParams(), $serverRequest->getAttributes());
        foreach ($recipients as $recipient) {
            if (array_key_exists('id_recipient', $searchCriteria)) {
                $recipientMeetSearchCriteria = $searchCriteria['id_recipient'] === (string)$recipient['id_recipient'];
            } elseif (array_key_exists('full_name', $searchCriteria)) {
                $recipientMeetSearchCriteria = $searchCriteria['full_name'] === $recipient['full_name'];
            } elseif (array_key_exists('birthday', $searchCriteria)) {
                $recipientMeetSearchCriteria = $searchCriteria['birthday'] === $recipient['birthday'];
            } elseif (array_key_exists('profession', $searchCriteria)) {
                $recipientMeetSearchCriteria = $searchCriteria['profession'] === $recipient['profession'];
            } else {
                $recipientMeetSearchCriteria = true;
            }
            if ($recipientMeetSearchCriteria) {
                $foundRecipients[] = Recipient::createFromArray($recipient);
            }
        }
        $this->logger->log('found recipients: ' . count($foundRecipients));

        return $foundRecipients;
    }


    /**
     * Загрузка данных о Знакомых
     *
     * @throws JsonException
     */
    private function loadData(): array
    {
        return (new JsonDataLoader())->loadData($this->pathToRecipients);
    }

    /**
     * обработка запроса поиска Знакомых
     *
     * @param ServerRequest $serverRequest
     *
     * @return HttpResponse
     * @throws JsonException
     */
    public function __invoke(ServerRequest $serverRequest): HttpResponse
    {
        $this->logger->log('dispatch "recipient" url');

        $resultOfParamValidation = $this->validateQueryParams($serverRequest);

        if (null === $resultOfParamValidation) {
            $recipients = $this->loadData();
            $foundRecipients = $this->searchRecipientInData($recipients, $serverRequest);
            $httpCode = $this->buildHttpCode($foundRecipients);
            $result = $this->buildResult($foundRecipients);
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