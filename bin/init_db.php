#!C:\php php
<?php

$dsn = "pgsql:host=localhost;port=5432;dbname=contact_list_db";
$dbConnection = new PDO($dsn, 'postgres', 'qwerty12');

/**
 * Импорт данных о пользователе
 */
$dbConnection->query('DELETE FROM users');

$userData = json_decode(file_get_contents(__DIR__ . '/../data/users.json'), true, 512, JSON_THROW_ON_ERROR);
foreach ($userData as $userItem) {
    $sql = "INSERT INTO users(id, login, password) VALUES ({$userItem['id']}, '{$userItem['login']}', '{$userItem['password']}')";
    $dbConnection->query($sql);
}

//$userFromDb = $dbConnection->query('SELECT * FROM users')->fetchAll(PDO::FETCH_ASSOC);

/**
 * Импорт данных о контактах
 */
$dbConnection->query('DELETE FROM contacts');

$contactsJsonData = [
    'recipients' => json_decode(file_get_contents(__DIR__ . '/../data/recipient.json'), true, 512, JSON_THROW_ON_ERROR),
    'kinsfolk' => json_decode(file_get_contents(__DIR__ . '/../data/kinsfolk.json'), true, 512, JSON_THROW_ON_ERROR),
    'customers' => json_decode(file_get_contents(__DIR__ . '/../data/customers.json'), true, 512, JSON_THROW_ON_ERROR),
    'colleagues' => json_decode(file_get_contents(__DIR__ . '/../data/colleagues.json'), true, 512, JSON_THROW_ON_ERROR)
];

foreach ($contactsJsonData as $type => $contactsCollection) {
    foreach ($contactsCollection as $contact) {
        $status = $contact['status'] ?? 'null';
        $ringtone = $contact['ringtone'] ?? 'null';
        $hotkey = $contact['hotkey'] ?? 'null';

        $contractNumber = $contact['contract_number'] ?? 'null';
        $averageTransactionAmount = $contact['average_transaction_amount'] ?? 'null';
        $discount = $contact['discount'] ?? 'null';
        $timeToCall = $contact['time_to_call'] ?? 'null';

        $department = $contact['department'] ?? 'null';
        $position = $contact['position'] ?? 'null';
        $roomNumber = $contact['room_number'] ?? 'null';

        $sql = <<<EOF
INSERT INTO contacts (id, full_name, birthday, profession, status, ringtone, hotkey, contract_number, average_transaction_amount, discount, time_to_call, department, position, room_number, category)
VALUES
(
 {$contact['id_recipient']},
 '{$contact['full_name']}',
 '{$contact['birthday']}',
 '{$contact['profession']}',
 '{$status}',
 '{$ringtone}',
 '{$hotkey}',
 {$contractNumber},
 {$averageTransactionAmount},
 '{$discount}',
 '{$timeToCall}',
 '{$department}',
 '{$position}',
 {$roomNumber},
 '{$type}'
);
EOF;
        $dbConnection->query($sql);
    }
}

/**
 * Импорт адресов контактов
 */
$dbConnection->query('DELETE FROM address');

$addressJsonData = json_decode(file_get_contents(__DIR__ . '/../data/address.json'), true, 512, JSON_THROW_ON_ERROR);
foreach ($addressJsonData as $addressItem) {
    $sql = "INSERT INTO address(id, id_recipient, address_data, status) VALUES ({$addressItem['id_address']}, {$addressItem['id_recipient']}, '{$addressItem['address']}', '{$addressItem['status']}')";
    $dbConnection->query($sql);
}

/**
 * Импорт черного списка
 */
$dbConnection->query('DELETE FROM contact_list');

$contactListJsonData = json_decode(
    file_get_contents(__DIR__ . '/../data/contact_list.json'),
    true,
    512,
    JSON_THROW_ON_ERROR
);
foreach ($contactListJsonData as $contactListItem) {
    $sql = "INSERT INTO contact_list(id, id_recipient, blacklist) VALUES ({$contactListItem['id_entry']}, {$contactListItem['id_recipient']}, '{$contactListItem['blacklist']}')";
    $dbConnection->query($sql);
}

/**
 * Импорт телефонной книги
 */
$dbConnection->query('DELETE FROM phone_number');

$phoneNumberJsonData = json_decode(
    file_get_contents(__DIR__ . '/../data/phone_number.json'),
    true,
    512,
    JSON_THROW_ON_ERROR
);
foreach ($phoneNumberJsonData as $phoneNumberItem) {
    $sql = "INSERT INTO phone_number(id, id_recipient, phone_number, operator) VALUES ({$phoneNumberItem['id_phone_number']}, {$phoneNumberItem['id_recipient']}, '{$phoneNumberItem['phone_number']}', '{$phoneNumberItem['operator']}')";
    $dbConnection->query($sql);
}

/**
 * Импорт данных об объекте значение мессенджеры
 */
$contactsJsonData = [
    'recipients' => json_decode(file_get_contents(__DIR__ . '/../data/recipient.json'), true, 512, JSON_THROW_ON_ERROR),
    'kinsfolk' => json_decode(file_get_contents(__DIR__ . '/../data/kinsfolk.json'), true, 512, JSON_THROW_ON_ERROR),
    'customers' => json_decode(file_get_contents(__DIR__ . '/../data/customers.json'), true, 512, JSON_THROW_ON_ERROR),
    'colleagues' => json_decode(file_get_contents(__DIR__ . '/../data/colleagues.json'), true, 512, JSON_THROW_ON_ERROR)
];

foreach ($contactsJsonData as $type => $contactsCollection) {
    foreach ($contactsCollection as $contact) {
        foreach ($contact['messengers'] as $messenger) {
            $sql = <<<EOF
INSERT INTO messengers (type_messenger, username, id_recipient) 
VALUES 
(
 '{$messenger['typeMessenger']}', 
 '{$messenger['username']}', 
 {$contact['id_recipient']}
)
EOF;

            $dbConnection->query($sql);
        }
    }
}