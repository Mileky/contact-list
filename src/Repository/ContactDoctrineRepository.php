<?php

namespace DD\ContactList\Repository;

use DD\ContactList\Entity\AbstractContact;
use DD\ContactList\Entity\Colleague;
use DD\ContactList\Entity\ContactRepositoryInterface;
use DD\ContactList\Entity\Customer;
use DD\ContactList\Entity\Kinsfolk;
use DD\ContactList\Entity\Recipient;
use DD\ContactList\Exception\RuntimeException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class ContactDoctrineRepository extends EntityRepository implements
    ContactRepositoryInterface
{
    private const REPLACED_CRITERIA = [
        'id'                         => 'id',
        'id_recipient'               => 'id',
        'full_name'                  => 'fullName',
        'birthday'                   => 'birthday',
        'profession'                 => 'profession',
        'status'                     => 'status',
        'ringtone'                   => 'ringtone',
        'hotkey'                     => 'hotkey',
        'contract_number'            => 'contractNumber',
        'average_transaction_amount' => 'averageTransactionAmount',
        'discount'                   => 'discount',
        'time_to_call'               => 'timeToCall',
        'department'                 => 'department',
        'position'                   => 'position',
        'room_number'                => 'roomNumber'
    ];

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
        if (false === isset($criteria['category']) || empty($criteria)) {
            return $this->loadAllContacts($criteria);
        }

        $prepareCriteria = $this->prepareCriteria($criteria);

        $recipientsData = [];
        $kinsfolkData = [];
        $customersData = [];
        $colleaguesData = [];


        if ($criteria['category'] === 'kinsfolk') {
            unset($criteria['category']);
            $kinsfolkData = $this->loadKinsfolk($prepareCriteria);
        } elseif ($criteria['category'] === 'customers') {
            unset($criteria['category']);
            $customersData = $this->loadCustomers($prepareCriteria);
        } elseif ($criteria['category'] === 'colleagues') {
            unset($criteria['category']);
            $colleaguesData = $this->loadColleagues($prepareCriteria);
        } elseif ($criteria['category'] === 'recipients') {
            unset($criteria['category']);
            $recipientsData = $this->loadRecipient($prepareCriteria);
        }

        return array_merge($recipientsData, $kinsfolkData, $customersData, $colleaguesData);
    }


    /**
     * @inheritDoc
     */
    public function findByCategory(array $criteria): array
    {
        return $this->findBy($criteria);
    }

    private function prepareCriteria(array $criteria): array
    {
        if (0 === count($criteria)) {
            return [];
        }

        $preparedCriteria = [];
        foreach ($criteria as $criteriaName => $criteriaValue) {
            if ($criteriaName === 'category') {
                continue;
            }
            if (array_key_exists($criteriaName, self::REPLACED_CRITERIA)) {
                $preparedCriteria[self::REPLACED_CRITERIA[$criteriaName]] = $criteriaValue;
            }
        }
        return $preparedCriteria;
    }

    /**
     * Загрузка данных о знакомых
     *
     * @param array $preparedCriteria
     *
     * @return array
     */
    private function loadRecipient(array $preparedCriteria): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select(['rec'])
            ->from(Recipient::class, 'rec');

        $this->buildWhere($preparedCriteria, $queryBuilder, 'rec');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Загрузка данных о коллегах
     *
     * @param array $preparedCriteria
     *
     * @return array
     */
    private function loadColleagues(array $preparedCriteria): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select(['col'])
            ->from(Colleague::class, 'col');

        $this->buildWhere($preparedCriteria, $queryBuilder, 'col');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Загрузка данных о клиентах
     *
     * @param array $preparedCriteria
     *
     * @return array
     */
    private function loadCustomers(array $preparedCriteria): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select(['cus'])
            ->from(Customer::class, 'cus');

        $this->buildWhere($preparedCriteria, $queryBuilder, 'cus');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Загрузка данных о родственниках
     *
     * @param array $preparedCriteria
     *
     * @return array
     */
    private function loadKinsfolk(array $preparedCriteria): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select(['kin'])
            ->from(Kinsfolk::class, 'kin');

        $this->buildWhere($preparedCriteria, $queryBuilder, 'kin');

        return $queryBuilder->getQuery()->getResult();
    }

    private function buildWhere(array $preparedCriteria, QueryBuilder $queryBuilder, ?string $alias): void
    {
        if (0 === count($preparedCriteria)) {
            return;
        }

        $whereExprAnd = $queryBuilder->expr()->andX();
        foreach ($preparedCriteria as $name => $value) {
            if (is_array($value)) {
                $queryBuilder->expr()->in("$alias.$name", ":name");
            } else {
                $whereExprAnd->add($queryBuilder->expr()->eq("$alias.$name", ":$name"));
            }
        }
        $queryBuilder->where($whereExprAnd);
        $queryBuilder->setParameters($preparedCriteria);
    }

    private function loadAllContacts(array $criteria): array
    {
        return parent::findBy($criteria);
    }
}
