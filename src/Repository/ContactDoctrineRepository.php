<?php

namespace DD\ContactList\Repository;

use DD\ContactList\Entity\AbstractContact;
use DD\ContactList\Entity\Colleague;
use DD\ContactList\Entity\ContactRepositoryInterface;
use DD\ContactList\Entity\Customer;
use DD\ContactList\Entity\Kinsfolk;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class ContactDoctrineRepository extends EntityRepository implements
    ContactRepositoryInterface
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
    public function findByCategory(array $criteria): array
    {
        return $this->findBy($criteria);
    }





}
