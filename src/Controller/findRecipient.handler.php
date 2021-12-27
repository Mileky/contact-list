<?php

namespace DD\ContactList\Controller;

use DD\ContactList\Entity\Recipient;
use DD\ContactList\Infrastructure\AppConfig;
use DD\ContactList\Infrastructure\Http\HttpResponse;
use DD\ContactList\Infrastructure\Http\ServerRequest;
use DD\ContactList\Infrastructure\Http\ServerResponseFactory;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;

use function DD\ContactList\Infrastructure\loadData;
use function DD\ContactList\Infrastructure\paramTypeValidation;

/**
 * Функция поиска знакомых по id или full_name
 *
 * @param ServerRequest $httpRequest
 * @param LoggerInterface $logger - параметр инкапсулирующий логгирование
 * @param AppConfig $appConfig
 *
 * @return HttpResponse - возвращает результат поиска по знакомым
 */
return static function (ServerRequest $httpRequest, LoggerInterface $logger, AppConfig $appConfig): HttpResponse {
    $recipients = loadData($appConfig->getPathToRecipients());
    $logger->log('dispatch "recipient" url');

    $paramValidations = [
        'id_recipient' => 'incorrect id_recipient',
        'full_name' => 'incorrect full_name',
        'birthday' => 'incorrect birthday',
        'profession' => 'incorrect profession'
    ];

    $requestParams = $httpRequest->getQueryParams();

    if (null === ($result = paramTypeValidation($paramValidations, $requestParams))) {
        $foundRecipients = [];
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
        $logger->log('found recipients not category: ' . count($foundRecipients));
        $result = [
            'httpCode' => 200,
            'result' => $foundRecipients
        ];
    }
    return ServerResponseFactory::createJsonResponse($result['httpCode'], $result['result']);
};
