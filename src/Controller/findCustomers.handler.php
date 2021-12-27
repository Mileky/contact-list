<?php

namespace DD\ContactList\Controller;

use DD\ContactList\Entity\Customer;
use DD\ContactList\Infrastructure\AppConfig;
use DD\ContactList\Infrastructure\Http\HttpResponse;
use DD\ContactList\Infrastructure\Http\ServerRequest;
use DD\ContactList\Infrastructure\Http\ServerResponseFactory;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;

use function DD\ContactList\Infrastructure\loadData;
use function DD\ContactList\Infrastructure\paramTypeValidation;

/**
 * Функция поиска клиента по id или full_name
 *
 * @param ServerRequest $httpRequest - объект http запроса
 * @param LoggerInterface $logger    - параметр инкапсулирующий логгирование
 * @param AppConfig $appConfig
 *
 * @return HttpResponse - возвращает результат поиска по авторам
 */

return static function (ServerRequest $httpRequest, LoggerInterface $logger, AppConfig $appConfig): HttpResponse {
    $customers = loadData($appConfig->getPathToCustomers());
    $logger->log('dispatch "customers" url');
    $paramValidations = [
        'id_recipient' => 'incorrect id_recipient',
        'full_name' => 'incorrect full_name',
        'birthday' => 'incorrect birthday',
        'profession' => 'incorrect profession',
        'contract_number' => ' incorrect contract_number',
        'average_transaction_amount' => 'incorrect average_transaction_amount',
        'discount' => 'incorrect discount',
        'time_to_call' => 'incorrect time_to_call'
    ];

    $requestParams = $httpRequest->getQueryParams();

    if (null === ($result = paramTypeValidation($paramValidations, $requestParams))) {
        $foundCustomers = [];
        foreach ($customers as $customer) {
            if (array_key_exists('id_recipient', $requestParams)) {
                $customerMeetSearchCriteria = $requestParams['id_recipient'] === (string)$customer['id_recipient'];
            } elseif (array_key_exists('full_name', $requestParams)) {
                $customerMeetSearchCriteria = $requestParams['full_name'] === $customer['full_name'];
            } elseif (array_key_exists('birthday', $requestParams)) {
                $customerMeetSearchCriteria = $requestParams['birthday'] === $customer['birthday'];
            } elseif (array_key_exists('profession', $requestParams)) {
                $customerMeetSearchCriteria = $requestParams['profession'] === $customer['profession'];
            } elseif (array_key_exists('contract_number', $requestParams)) {
                $customerMeetSearchCriteria = $requestParams['contract_number'] === $customer['contract_number'];
            } elseif (array_key_exists('average_transaction_amount', $requestParams)) {
                $customerMeetSearchCriteria = $requestParams['average_transaction_amount'] === (string)$customer['average_transaction_amount'];
            } elseif (array_key_exists('discount', $requestParams)) {
                $customerMeetSearchCriteria = $requestParams['discount'] === $customer['discount'];
            } elseif (array_key_exists('time_to_call', $requestParams)) {
                $customerMeetSearchCriteria = $requestParams['time_to_call'] === $customer['time_to_call'];
            } else {
                $customerMeetSearchCriteria = true;
            }
            if ($customerMeetSearchCriteria) {
                $foundCustomers[] = Customer::createFromArray($customer);
            }
        }
        $logger->log('found customers not category: ' . count($foundCustomers));
        $result = [
            'httpCode' => 200,
            'result' => $foundCustomers
        ];
    }
    return ServerResponseFactory::createJsonResponse($result['httpCode'], $result['result']);
};
