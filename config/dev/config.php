<?php

return [
    /**
     *  Путь до файла с данными о получателях
     */
    'pathToRecipients'  => __DIR__ . '/../../data/recipient.json',
    /**
     *  Путь до файла с данными о клиентах
     */
    'pathToCustomers'   => __DIR__ . '/../../data/customers.json',
    /**
     *  Путь до файла с данными о родне
     */
    'pathToKinsfolk'    => __DIR__ . '/../../data/kinsfolk.json',
    /**
     *  Путь до файла с данными о коллегах
     */
    'pathToColleagues'  => __DIR__ . '/../../data/colleagues.json',

    /**
     *  Путь до файла с данными о контактном листе
     */
    'pathToContactList' => __DIR__ . '/../../data/contact_list.json',

    /**
     * Путь до файла с данными о адресах контактов
     */
    'pathToAddress'     => __DIR__ . '/../../data/address.json',

    /**
     * Путь до файла с данными о пользователях
     */
    'pathToUsers'       => __DIR__ . '/../../data/users.json',


    /**
     * Путь до файла лога
     */
    'pathToLogFile'     => __DIR__ . '/../../var/log/app.log',

    /**
     * Uri, по которому можно открыть форму аутентификации
     */
    'loginUri'          => '/login',

//    /**
//     *  Тип используемого логера
//     */
//    'loggerType' => 'fileLogger',

    /**
     * Сокрытие сообщение о ошибках
     */
    'hideErrorMessage'  => false,
];