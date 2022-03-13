<?php

namespace DD\ContactList\Repository;

use DD\ContactList\Entity\AbstractContact;
use DD\ContactList\Entity\Colleague;
use DD\ContactList\Entity\ContactRepositoryInterface;
use DD\ContactList\Entity\Customer;
use DD\ContactList\Entity\Kinsfolk;
use DD\ContactList\Entity\Recipient;
use DD\ContactList\Infrastructure\Db\ConnectionInterface;
use DD\ContactList\Infrastructure\Exception;
use DD\ContactList\ValueObject\Messenger;

class ContactDbRepository implements ContactRepositoryInterface
{

    private const ALLOWED_CRITERIA = [
        'id',
        'full_name',
        'birthday',
        'profession',
        'contract_number',
        'average_transaction_amount',
        'discount',
        'time_to_call',
        'status',
        'ringtone',
        'hotkey',
        'department',
        'position',
        'room_number',
        'category',
        'list_id'
    ];

    /**
     * Компонент отвечающий за соединение с БД
     *
     * @var ConnectionInterface
     */
    private ConnectionInterface $connection;

    /**
     * @param ConnectionInterface $connection - Компонент отвечающий за соединение с БД
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @inheritDoc
     */
    public function findBy(array $criteria = []): array
    {
        /** костылек */
        if (isset($criteria['id_recipient'])) {
            $criteria['id'] = $criteria['id_recipient'];
            unset($criteria['id_recipient']);
        }
        $this->validateCriteria($criteria);

        $contactsData = $this->loadContactsData($criteria);
        $messengersData = $this->loadMessengersData($contactsData);

        return $this->buildContactsEntities($contactsData, $messengersData);
    }

    /**
     * @inheritDoc
     */
    public function findByCategory(array $criteria): array
    {
        return $this->findBy($criteria);
    }

    /**
     * Валидация критериев поиска
     *
     * @param array $criteria
     *
     * @return void
     */
    private function validateCriteria(array $criteria): void
    {
        $invalidCriteria = array_diff(array_keys($criteria), self::ALLOWED_CRITERIA);

        if (count($invalidCriteria) > 0) {
            $errMsg = 'Неподдерживаемые критерии поиска текстовых документов' . implode(', ', $invalidCriteria);
            throw new Exception\RuntimeException($errMsg);
        }
    }

    /**
     * Загрузка данных о контактах
     *
     * @param array $criteria
     *
     * @return array
     */
    private function loadContactsData(array $criteria): array
    {
        $sql = <<<EOF
SELECT 
id, 
full_name, 
birthday, 
profession, 
status, 
ringtone, 
hotkey, 
contract_number, 
average_transaction_amount, 
discount, 
time_to_call, 
department, 
position, 
room_number, 
category 
FROM contacts
EOF;

        $whereParts = [];
        $whereParams = [];

        foreach ($criteria as $criteriaName => $criteriaValue) {
            if ('list_id' === $criteriaName) {
                if (false === is_array($criteriaValue)) {
                    throw new Exception\RuntimeException('Некорректный список id контактов');
                }
                $idParts = [];
                foreach ($criteriaValue as $index => $idValue) {
                    $idParts[] = ":id_$index";
                    $whereParams[":id_$index"] = $idValue;
                }
                if (count($idParts) > 0) {
                    $whereParts[] = 'id IN (' . implode(', ', $idParts) . ')';
                }
            } else {
                $whereParts[] = "$criteriaName=:$criteriaName";
                $whereParams[$criteriaName] = $criteriaValue;
            }
        }

        if (count($whereParts) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $whereParts);
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($whereParams);

        return $stmt->fetchAll();
    }

    /**
     * Создание сущностей Контакт из БД
     *
     * @param array $contactsData
     * @param array $messengersData
     *
     * @return array
     */
    private function buildContactsEntities(array $contactsData, array $messengersData): array
    {
        $contactsEntities = [];
        foreach ($contactsData as $contactsItem) {
            $contactsItem['messengers'] = false === array_key_exists($contactsItem['id'], $messengersData)
                ? []
                : $messengersData[$contactsItem['id']];

            $birthday = \DateTimeImmutable::createFromFormat('Y-m-d', $contactsItem['birthday']);
            $contactsItem['birthday'] = $birthday->format('d.m.Y');

            if ('recipients' === $contactsItem['category']) {
                $contactsEntities[$contactsItem['id']] = Recipient::createFromArray($contactsItem);
            } elseif ('customers' === $contactsItem['category']) {
                $contactsEntities[$contactsItem['id']] = Customer::createFromArray($contactsItem);
            } elseif ('colleagues' === $contactsItem['category']) {
                $contactsEntities[$contactsItem['id']] = Colleague::createFromArray($contactsItem);
            } elseif ('kinsfolk' === $contactsItem['category']) {
                $contactsEntities[$contactsItem['id']] = Kinsfolk::createFromArray($contactsItem);
            }
        }

        return $contactsEntities;
    }

    /**
     * Загрузка данных о мессенджерах контактов
     *
     * @param array $contactsData
     *
     * @return Messenger[]
     */
    private function loadMessengersData(array $contactsData): array
    {
        $idListWhereParts = [];
        $whereParams = [];

        foreach ($contactsData as $contact) {
            $idListWhereParts[] = "id_recipient=:id_{$contact['id']}";
            $whereParams["id_{$contact['id']}"] = $contact['id'];
        }

        if (0 === count($idListWhereParts)) {
            return [];
        }

        $sql = <<<EOF
SELECT
type_messenger, username, id_recipient
FROM messengers
EOF;

        $sql .= ' WHERE ' . implode(' OR ', $idListWhereParts);

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($whereParams);

        $messengersData = $stmt->fetchAll();

        $foundMessengers = [];

        foreach ($messengersData as $messenger) {
            $obj = new Messenger($messenger['type_messenger'], $messenger['username']);

            if (false === array_key_exists($messenger['id_recipient'], $foundMessengers)) {
                $foundMessengers[$messenger['id_recipient']] = [];
            }
            $foundMessengers[$messenger['id_recipient']][] = $obj;
        }

        return $foundMessengers;
    }
}