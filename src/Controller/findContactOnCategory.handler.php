<?php

namespace DD\ContactList\Controller;

use DD\ContactList\Entity\Customer;
use DD\ContactList\Entity\Kinsfolk;
use DD\ContactList\Entity\Recipient;
use DD\ContactList\Entity\Colleague;
use DD\ContactList\Infrastructure\AppConfig;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;

use function DD\ContactList\Infrastructure\loadData;

/**
 * Функция поиска контактов по категории
 *
 * @param array $request          - параметры которые передаёт пользователь
 * @param LoggerInterface $logger - параметр инкапсулирующий логгирование
 *
 * @return array - возвращает результат поиска по категориям
 */
return static function (array $request, LoggerInterface $logger, AppConfig $appConfig): array {
    $customers = loadData($appConfig->getPathToCustomers());
    $recipients = loadData($appConfig->getPathToRecipients());
    $kinsfolk = loadData($appConfig->getPathToKinsfolk());
    $colleagues = loadData($appConfig->getPathToColleagues());

    $logger->log('dispatch "category" url');

    if (!array_key_exists('category', $request)) {
        return [
            'httpCode' => 500,
            'result' => [
                'status' => 'fail',
                'message' => 'empty category'
            ]
        ];
    }

    $foundRecipientsOnCategory = [];

    if ($request['category'] === 'customers') {
        foreach ($customers as $customer) {
            $foundRecipientsOnCategory[] = Customer::createFromArray($customer);
        }

        $logger->log('dispatch category "customers"');
        $logger->log('found customers: ' . count($foundRecipientsOnCategory));

    } elseif ($request['category'] === 'recipients') {
        foreach ($recipients as $recipient) {
            $foundRecipientsOnCategory[] = Recipient::createFromArray($recipient);
        }

        $logger->log('dispatch category "recipients"');
        $logger->log('found customers: ' . count($foundRecipientsOnCategory));

    } elseif ($request['category'] === 'kinsfolk') {
        foreach ($kinsfolk as $kinsfolkValue) {
            $foundRecipientsOnCategory[] = Kinsfolk::createFromArray($kinsfolkValue);
        }

        $logger->log('dispatch category "kinsfolk"');
        $logger->log('found kinsfolk: ' . count($foundRecipientsOnCategory));

    } elseif ($request['category'] === 'colleagues') {
        foreach ($colleagues as $colleague) {
            $foundRecipientsOnCategory[] = Colleague::createFromArray($colleague);
        }

        $logger->log('dispatch category "colleagues"');
        $logger->log('found colleagues: ' . count($foundRecipientsOnCategory));

    } else {
        return [
            'httpCode' => 500,
            'result' => [
                'status' => 'fail',
                'message' => 'dispatch category nothing'
            ]
        ];
    }

    return [
        'httpCode' => 200,
        'result' => $foundRecipientsOnCategory
    ];
};