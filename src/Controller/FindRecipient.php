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


final class FindRecipient implements ControllerInterface
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
        $requestParams = $serverRequest->getQueryParams();
        foreach ($recipients as $recipient) {
            if (array_key_exists('id_recipient', $requestParams)) {
                $recipientMeetSearchCriteria = $requestParams['id_recipient'] === (string)$recipient['id_recipient'];
            } elseif (array_key_exists('full_name', $requestParams)) {
                $recipientMeetSearchCriteria = $requestParams['full_name'] === $recipient['full_name'];
            } elseif (array_key_exists('birthday', $requestParams)) {
                $recipientMeetSearchCriteria = $requestParams['birthday'] === $recipient['birthday'];
            } elseif (array_key_exists('profession', $requestParams)) {
                $recipientMeetSearchCriteria = $requestParams['profession'] === $recipient['profession'];
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
            $httpCode = 200;
            $result = $this->searchRecipientInData($recipients, $serverRequest);
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