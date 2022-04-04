<?php

namespace DD\ContactList\Repository;

use DateTimeImmutable;
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
    /**
     * Критерии поиска
     */
    private const SEARCH_CRITERIA_TO_SQL_PARTS = [
        'id' => 'c.id',
        'full_name' => 'c.full_name',
        'birthday' => 'c.birthday',
        'profession' => 'c.profession',
        'contract_number' => 'cc2.contract_number',
        'average_transaction_amount' => 'cc2.average_transaction_amount',
        'discount' => 'cc2.discount',
        'time_to_call' => 'cc2.time_to_call',
        'status' => 'ck.status',
        'ringtone' => 'ck.ringtone',
        'hotkey' => 'ck.hotkey',
        'department' => 'cc.department',
        'position' => 'cc.position',
        'room_number' => 'cc.room_number',
        'category' => 'c.category',
        'list_id' => null
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
        $invalidCriteria = array_diff(array_keys($criteria), self::SEARCH_CRITERIA_TO_SQL_PARTS);

        if (count($invalidCriteria) > 0) {
            $errMsg = 'Неподдерживаемые критерии поиска контактов' . implode(', ', $invalidCriteria);
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
    c.id AS id,
    c.full_name AS full_name,
    c.birthday AS birthday,
    c.profession AS profession,
    ck.status AS status,
    ck.ringtone AS ringtone,
    ck.hotkey AS hotkey,
    cc2.contract_number AS contract_number,
    cc2.average_transaction_amount AS average_transaction_amount,
    cc2.discount AS discount,
    cc2.time_to_call AS time_to_call,
    cc.department AS department,
    cc.position AS position,
    cc.room_number AS room_number,
    c.category
FROM contacts AS c
         LEFT JOIN contacts_colleagues cc ON c.id = cc.id
         LEFT JOIN contacts_customers cc2 ON c.id = cc2.id
         LEFT JOIN contacts_kinsfolk ck ON c.id = ck.id
EOF;

        $whereParts = [];
        $whereParams = [];
        $notSupportedSearchCriteria = [];

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
                    $whereParts[] = 'c.id IN (' . implode(', ', $idParts) . ')';
                }
            } elseif (array_key_exists($criteriaName, self::SEARCH_CRITERIA_TO_SQL_PARTS)) {
                $sqlParts = self::SEARCH_CRITERIA_TO_SQL_PARTS[$criteriaName];
                $whereParts[] = "$sqlParts=:$criteriaName";
                $whereParams[$criteriaName] = $criteriaValue;
            } else {
                $notSupportedSearchCriteria[] = $criteriaName;
            }

            if (count($notSupportedSearchCriteria) > 0) {
                throw new Exception\RuntimeException(
                    'Неподдерживаемые критерии поиска контактов: '
                    . implode(', ', $notSupportedSearchCriteria)
                );
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

            $birthday = DateTimeImmutable::createFromFormat('Y-m-d', $contactsItem['birthday']);
            $contactsItem['birthday'] = $birthday;

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
