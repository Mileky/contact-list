<?php

namespace DD\ContactList\Repository;

use DD\ContactList\Entity\Address;
use DD\ContactList\Entity\Address\Status;
use DD\ContactList\Entity\AddressRepositoryInterface;
use DD\ContactList\Entity\ContactRepositoryInterface;
use DD\ContactList\Infrastructure\Db\ConnectionInterface;

class AddressDbRepository implements AddressRepositoryInterface
{
    private const BASE_SEARCH_SQL = <<<EOF
SELECT a.id                         AS id,
       a.address_data               AS address_data,
       ads.name                     AS status,
       c.id                         AS contact_id
FROM address AS a
         LEFT JOIN address_to_contact atc ON a.id = atc.address_id
         LEFT JOIN contacts c ON c.id = atc.recipient_id
         LEFT JOIN address_status ads on ads.id = a.status_id
EOF;


    /**
     * Компонент отвечающий за соединение с БД
     *
     * @var ConnectionInterface
     */
    private ConnectionInterface $connection;

    /**
     * Репозиторий для работы с контактами
     *
     * @var ContactRepositoryInterface
     */
    private ContactRepositoryInterface $contactRepository;

    /**
     * @param ConnectionInterface $connection               - Компонент отвечающий за соединение с БД
     * @param ContactRepositoryInterface $contactRepository - Репозиторий для работы с контактами
     */
    public function __construct(ConnectionInterface $connection, ContactRepositoryInterface $contactRepository)
    {
        $this->connection = $connection;
        $this->contactRepository = $contactRepository;
    }

    /**
     * @inheritDoc
     */
    public function findBy(array $criteria): array
    {
        $sql = self::BASE_SEARCH_SQL;
        $addressData = $this->connection->query($sql)->fetchAll();

        return $this->buildEntity($addressData);
    }

    /**
     * @inheritDoc
     */
    public function nextId(): int
    {
        $sql = <<<EOF
SELECT nextval('address_id_seq') AS next_id
EOF;

        return current($this->connection->query($sql)->fetchAll())['next_id'];
    }

    /**
     * @inheritDoc
     */
    public function add(Address $address): Address
    {
        $sql = <<<EOF
INSERT INTO address (id, address_data, status_id) 
(
    SELECT :id, :address_data, ads.id
    FROM address_status AS ads
    WHERE ads.name = :status          
)
EOF;

        $values = [
            'id' => $address->getId(),
            'address_data' => $address->getAddress(),
            'status' => $address->getStatus()->getName()
        ];

        $this->connection->prepare($sql)->execute($values);

        $this->saveAddressToContact($address);

        return $address;
    }

    /**
     * Создание сущности Адрес
     *
     * @param array $data
     *
     * @return Address[]
     */
    private function buildEntity(array $data): array
    {
        $addressData = [];
        $contact = [];
        foreach ($data as $row) {
            if (false === array_key_exists($row['id'], $addressData)) {
                $addressData[$row['id']] = [
                    'id' => $row['id'],
                    'id_recipient' => [],
                    'address_data' => $row['address_data'],
                    'status' => new Status($row['status']),
                ];
            }

            if (false === array_key_exists($row['contact_id'], $contact)) {
                $contact[$row['contact_id']] = current($this->contactRepository->findBy(['id' => $row['contact_id']]));
            }
            $addressData[$row['id']]['id_recipient'][$row['contact_id']] = $contact[$row['contact_id']];
        }
        $addressEntities = [];
        foreach ($addressData as $item) {
            $addressEntities[] = Address::createFromArray($addressData[$item['id']]);
        }

        return $addressEntities;
    }

    private function saveAddressToContact(Address $address): void
    {
        $this->connection
            ->prepare('DELETE FROM address_to_contact WHERE address_id = :addressId')
            ->execute(['addressId' => $address->getId()]);

        $insertParts = [];
        $insertParams = [];
        foreach ($address->getRecipients() as $index => $contact) {
            $insertParts[] = "(:addressId_$index, :recipientId_$index)";
            $insertParams["addressId_$index"] = $address->getId();
            $insertParams["recipientId_$index"] = $contact->getId();
        }

        if (count($insertParts) > 0) {
            $values = implode(', ', $insertParts);

            $sql = <<<EOF
INSERT INTO address_to_contact(address_id, recipient_id) VALUES $values
EOF;
            $this->connection->prepare($sql)->execute($insertParams);
        }
    }
}
