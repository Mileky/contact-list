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
        'id_recipient' => 'c.id',
        'full_name' => 'c.fullName',
        'birthday' => 'c.birthday',
        'profession' => 'c.profession',
        'status' => 'kin.status',
        'ringtone' => 'kin.ringtone',
        'hotkey' => 'kin.hotkey',
        'contract_number' => 'cus.contractNumber',
        'average_transaction_amount' => 'cus.averageTransactionAmount',
        'discount' => 'cus.discount',
        'time_to_call' => 'cus.timeToCall',
        'department' => 'col.department',
        'position' => 'col.position',
        'room_number' => 'col.roomNumber'
    ];

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
        if (empty($criteria)) {
            return $this->loadAllContacts();
        }

        $criteriaForDefinitionsType = $this->prepareCriteria($criteria);

        $recipientsData = [];
        $kinsfolkData = [];
        $customersData = [];
        $colleaguesData = [];


        foreach ($criteriaForDefinitionsType as $name => $value) {
            if (strpos($name, 'kin.') === 0) {
                $kinsfolkData = $this->loadKinsfolk($criteria, $name);
            } elseif (strpos($name, 'cus.') === 0) {
                $customersData = $this->loadCustomers($criteria, $name);
            } elseif (strpos($name, 'col.') === 0) {
                $colleaguesData = $this->loadColleagues($criteria, $name);
            } else {
                $recipientsData = $this->loadRecipient($criteria, $name);
            }
        }

        return array_merge($recipientsData, $kinsfolkData, $customersData, $colleaguesData);
    }


    /**
     * @inheritDoc
     */
    public function findByCategory(array $criteria): array
    {
        $type = $criteria['category'];
        if ('kinsfolk' === $type) {
            $contactsData = $this->loadKinsfolk([]);
        } elseif ('customers' === $type) {
            $contactsData = $this->loadCustomers([]);
        } elseif ('colleagues' === $type) {
            $contactsData = $this->loadColleagues([]);
        } elseif ('recipients' === $type) {
            $contactsData = $this->loadRecipient([]);
        } else {
            throw new RuntimeException('Неверная категория контакта');
        }

        return $contactsData;
    }

    private function prepareCriteria(array $criteria): array
    {
        if (0 === count($criteria)) {
            return [];
        }

        $preparedCriteria = [];
        foreach ($criteria as $criteriaName => $criteriaValue) {
            if (array_key_exists($criteriaName, self::REPLACED_CRITERIA)) {
                $preparedCriteria[self::REPLACED_CRITERIA[$criteriaName]] = $criteriaValue;
            }
        }
        return empty($preparedCriteria) ? $criteria : $preparedCriteria;
    }

    /**
     * Загрузка данных о знакомых
     *
     * @param array $preparedCriteria
     * @param string|null $alias
     *
     * @return array
     */
    private function loadRecipient(array $preparedCriteria, string $alias = null): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select(['c'])
            ->from(AbstractContact::class, 'c');

        $this->buildWhere($preparedCriteria, $queryBuilder, $alias);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Загрузка данных о коллегах
     *
     * @param array $preparedCriteria
     * @param string|null $alias
     *
     * @return array
     */
    private function loadColleagues(array $preparedCriteria, string $alias = null): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select(['col'])
            ->from(Colleague::class, 'col');

        $this->buildWhere($preparedCriteria, $queryBuilder, $alias);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Загрузка данных о клиентах
     *
     * @param array $preparedCriteria
     * @param string|null $alias
     *
     * @return array
     */
    private function loadCustomers(array $preparedCriteria, string $alias = null): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select(['cus'])
            ->from(Customer::class, 'cus');

        $this->buildWhere($preparedCriteria, $queryBuilder, $alias);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Загрузка данных о родственниках
     *
     * @param array $preparedCriteria
     * @param string|null $alias
     *
     * @return array
     */
    private function loadKinsfolk(array $preparedCriteria, string $alias = null): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select(['kin'])
            ->from(Kinsfolk::class, 'kin');

        $this->buildWhere($preparedCriteria, $queryBuilder, $alias);

        return $queryBuilder->getQuery()->getResult();
    }

    private function buildWhere(array $preparedCriteria, QueryBuilder $queryBuilder, ?string $alias): void
    {
        if (0 === count($preparedCriteria)) {
            return;
        }

        $whereExprAnd = $queryBuilder->expr()->andX();
        foreach ($preparedCriteria as $name => $value) {
            $whereExprAnd->add($queryBuilder->expr()->eq($alias, ":$name"));
        }
        $queryBuilder->where($whereExprAnd);
        $queryBuilder->setParameters($preparedCriteria);
    }

    private function loadAllContacts()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select(['con'])
            ->from(AbstractContact::class, 'con')
            ->orderBy('con.id');

        return $queryBuilder->getQuery()->getResult();
    }
}
