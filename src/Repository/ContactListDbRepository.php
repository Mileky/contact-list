<?php

namespace DD\ContactList\Repository;

use DD\ContactList\Entity\ContactList;
use DD\ContactList\Entity\ContactListRepositoryInterface;
use DD\ContactList\Entity\ContactRepositoryInterface;
use DD\ContactList\Infrastructure\Db\ConnectionInterface;

class ContactListDbRepository implements ContactListRepositoryInterface
{
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
     * @param ConnectionInterface $connection - Компонент отвечающий за соединение с БД
     */
    public function __construct(ConnectionInterface $connection, ContactRepositoryInterface $contactRepository)
    {
        $this->connection = $connection;
        $this->contactRepository = $contactRepository;
    }

    /**
     * @inheritDoc
     */
    public function findById(int $contactId): array
    {
        $contactsData = $this->contactRepository->findBy(['id_recipient' => $contactId]);
        $sql = <<<EOF
SELECT
id, id_recipient, blacklist
FROM contact_list
WHERE id_recipient = :id_recipient
EOF;

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id_recipient' => $contactId]);
        $contactListData = current($stmt->fetchAll());
        $contactListData['id_recipient'] = current($contactsData);
        $contactListItem[] = ContactList::createFromArray($contactListData);
        return $contactListItem;
    }

    /**
     * @inheritDoc
     */
    public function save(ContactList $contactList): ContactList
    {
        $sql = <<<EOF
UPDATE contact_list
SET
id = :id,
    id_recipient = :id_recipient,
    blacklist = :blacklist
WHERE id_recipient = :id_recipient
EOF;

        $values = [
            'id' => $contactList->getId(),
            'id_recipient' => $contactList->getRecipient()->getIdRecipient(),
            'blacklist' => $contactList->isBlacklist()
        ];

        $this->connection->prepare($sql)->execute($values);

        return $contactList;
    }
}
