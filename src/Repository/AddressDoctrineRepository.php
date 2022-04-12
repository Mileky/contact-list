<?php

namespace DD\ContactList\Repository;

use DD\ContactList\Entity\Address;
use DD\ContactList\Entity\AddressRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class AddressDoctrineRepository extends EntityRepository implements
    AddressRepositoryInterface
{
    /**
     * @param array      $criteria
     * @param array|null $orderBy
     * @param            $limit
     * @param            $offset
     *
     * @return array|object[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select(['a', 'r'])
            ->from(Address::class, 'a')
            ->leftJoin('a.recipients', 'r');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @inheritDoc
     */
    public function add(Address $address): Address
    {
        $this->getEntityManager()->persist($address);
        return $address;
    }
}
