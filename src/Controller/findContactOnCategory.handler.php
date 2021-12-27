<?php

namespace DD\ContactList\Controller;

use DD\ContactList\Entity\Customer;
use DD\ContactList\Entity\Kinsfolk;
use DD\ContactList\Entity\Recipient;
use DD\ContactList\Entity\Colleague;
use DD\ContactList\Infrastructure\AppConfig;
use DD\ContactList\Infrastructure\Http\HttpResponse;
use DD\ContactList\Infrastructure\Http\ServerRequest;
use DD\ContactList\Infrastructure\Http\ServerResponseFactory;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;

use JsonException;

use function DD\ContactList\Infrastructure\loadData;

/**
 * Функция поиска контактов по категории
 *
 * @param ServerRequest $httpRequest - http запрос
 * @param LoggerInterface $logger    - параметр инкапсулирующий логгирование
 * @param AppConfig $appConfig
 *
 * @return HttpResponse - возвращает результат поиска по категориям
 * @throws JsonException
 */
return static function (ServerRequest $httpRequest, LoggerInterface $logger, AppConfig $appConfig): HttpResponse {
    $customers = loadData($appConfig->getPathToCustomers());
    $recipients = loadData($appConfig->getPathToRecipients());
    $kinsfolk = loadData($appConfig->getPathToKinsfolk());
    $colleagues = loadData($appConfig->getPathToColleagues());

    $requestParams = $httpRequest->getQueryParams();

    $logger->log('dispatch "category" url');

    if (!array_key_exists('category', $requestParams)) {
        $result = [
            'httpCode' => 500,
            'result' => [
                'status' => 'fail',
                'message' => 'empty category'
            ]
        ];
    }

    $foundRecipientsOnCategory = [];

    if ($requestParams['category'] === 'customers') {
        foreach ($customers as $customer) {
            $foundRecipientsOnCategory[] = Customer::createFromArray($customer);
        }
        $logger->log('dispatch category "customers"');
        $logger->log('found customers: ' . count($foundRecipientsOnCategory));
    } elseif ($requestParams['category'] === 'recipients') {
        foreach ($recipients as $recipient) {
            $foundRecipientsOnCategory[] = Recipient::createFromArray($recipient);
        }

        $logger->log('dispatch category "recipients"');
        $logger->log('found customers: ' . count($foundRecipientsOnCategory));
    } elseif ($requestParams['category'] === 'kinsfolk') {
        foreach ($kinsfolk as $kinsfolkValue) {
            $foundRecipientsOnCategory[] = Kinsfolk::createFromArray($kinsfolkValue);
        }

        $logger->log('dispatch category "kinsfolk"');
        $logger->log('found kinsfolk: ' . count($foundRecipientsOnCategory));
    } elseif ($requestParams['category'] === 'colleagues') {
        foreach ($colleagues as $colleague) {
            $foundRecipientsOnCategory[] = Colleague::createFromArray($colleague);
        }

        $logger->log('dispatch category "colleagues"');
        $logger->log('found colleagues: ' . count($foundRecipientsOnCategory));
    } else {
        $result = [
            'httpCode' => 500,
            'result' => [
                'status' => 'fail',
                'message' => 'dispatch category nothing'
            ]
        ];
    }

    $result = [
        'httpCode' => 200,
        'result' => $foundRecipientsOnCategory
    ];

    return ServerResponseFactory::createJsonResponse($result['httpCode'], $result['result']);
};