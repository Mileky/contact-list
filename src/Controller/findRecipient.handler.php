<?php

namespace DD\ContactList\Controller;

use DD\ContactList\Entity\Recipient;
use DD\ContactList\Infrastructure\AppConfig;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;

use function DD\ContactList\Infrastructure\loadData;
use function DD\ContactList\Infrastructure\paramTypeValidation;

/**
 * Функция поиска знакомых по id или full_name
 *
 * @param array $request          - параметры которые передаёт пользователь
 * @param LoggerInterface $logger - параметр инкапсулирующий логгирование
 *
 * @return array - возвращает результат поиска по знакомым
 */
return static function (array $request, LoggerInterface $logger, AppConfig $appConfig): array {
    $recipients = loadData($appConfig->getPathToRecipients());
    $logger->log('dispatch "recipient" url');

    $paramValidations = [
        'id_recipient' => 'incorrect id_recipient',
        'full_name' => 'incorrect full_name',
        'birthday' => 'incorrect birthday',
        'profession' => 'incorrect profession'
    ];

    if (null === ($result = paramTypeValidation($paramValidations, $request))) {
        $foundRecipients = [];
        foreach ($recipients as $recipient) {
            if (array_key_exists('id_recipient', $request)) {
                $recipientMeetSearchCriteria = $request['id_recipient'] === (string)$recipient['id_recipient'];
            } elseif (array_key_exists('full_name', $request)) {
                $recipientMeetSearchCriteria = $request['full_name'] === $recipient['full_name'];
            } elseif (array_key_exists('birthday', $request)) {
                $recipientMeetSearchCriteria = $request['birthday'] === $recipient['birthday'];
            } elseif (array_key_exists('profession', $request)) {
                $recipientMeetSearchCriteria = $request['profession'] === $recipient['profession'];
            } else {
                $recipientMeetSearchCriteria = true;
            }
            if ($recipientMeetSearchCriteria) {
                $foundRecipients[] = Recipient::createFromArray($recipient);
            }
        }
        $logger->log('found recipients not category: ' . count($foundRecipients));
        return [
            'httpCode' => 200,
            'result' => $foundRecipients
        ];
    }
    return $result;
};
