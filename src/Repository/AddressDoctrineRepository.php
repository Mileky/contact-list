<?php

namespace DD\ContactList\Repository;

use DD\ContactList\Entity\Address;
use DD\ContactList\Entity\AddressRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use DD\ContactList\Exception;

class AddressDoctrineRepository extends EntityRepository implements
    AddressRepositoryInterface
{
    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param $limit
     * @param $offset
     *
     * @return array|object[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }


    /**
     * @inheritDoc
     */
    public function nextId(): int
    {
        throw new Exception\RuntimeException('не реализовано');
    }

    /**
     * @inheritDoc
     */
    public function add(Address $address): Address
    {
        throw new Exception\RuntimeException('не реализовано');
    }
}