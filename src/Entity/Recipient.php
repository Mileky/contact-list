<?php

namespace DD\ContactList\Entity;

use DD\ContactList\Exception;

/**
 * Получатель
 */
final class Recipient extends AbstractContact
{
    /**
     * @param int $id_recipient  - id Получателя
     * @param string $full_name  - Полное имя получателя
     * @param string $birthday   - Дата рождения получателя
     * @param string $profession - Профессия получателя
     * @param array $messengers  - Данные о мессенджере, в котором есть пользователь
     */
    public function __construct(
        int $id_recipient,
        string $full_name,
        string $birthday,
        string $profession,
        array $messengers
    ) {
        parent::__construct($id_recipient, $full_name, $birthday, $profession, $messengers);
    }

    /**
     * Создание сущности "Получатель" из массива
     *
     * @param array $data
     *
     * @return Recipient
     */
    public static function createFromArray(array $data): Recipient
    {
        $requiredFields = [
            'id_recipient',
            'full_name',
            'birthday',
            'profession',
            'messengers'
        ];

        $missingFields = array_diff($requiredFields, array_keys($data));

        if (count($missingFields) > 0) {
            $errMsg = sprintf('Отсутствуют обязательные элементы: %s', implode(',', $missingFields));
            throw new Exception\InvalidDataStructureException($errMsg);
        }

        return new Recipient(
            $data['id_recipient'],
            $data['full_name'],
            $data['birthday'],
            $data['profession'],
            $data['messengers']
        );
    }

}