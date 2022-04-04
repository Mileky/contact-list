<?php

namespace DD\ContactList\Repository;

use DD\ContactList\Entity\ContactList;
use DD\ContactList\Entity\ContactListRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use DD\ContactList\Exception;

class ContactListDoctrineRepository extends EntityRepository implements
    ContactListRepositoryInterface
{
    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param $limit
     * @param $offset
     *
     * @return array|object[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }


    /**
     * @inheritDoc
     */
    public function findById(int $contactId): array
    {
        throw new Exception\RuntimeException('не реализовано');
    }

    /**
     * @inheritDoc
     */
    public function save(ContactList $contactList): ContactList
    {
        throw new Exception\RuntimeException('не реализовано');
    }
}