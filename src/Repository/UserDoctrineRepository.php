<?php

namespace DD\ContactList\Repository;

use DD\ContactList\Entity\UserRepositoryInterface;
use DD\ContactList\Infrastructure\Auth\UserDataStorageInterface;
use DD\ContactList\Repository\UserRepository\UserDataProvider;
use Doctrine\ORM\EntityRepository;
use DD\ContactList\Exception;

class UserDoctrineRepository extends EntityRepository implements
    UserDataStorageInterface,
    UserRepositoryInterface
{
    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param $limit
     * @param $offset
     *
     * @return UserDataProvider[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }


    /**
     * @inheritDoc
     */
    public function findUserByLogin(string $login): ?UserDataProvider
    {
        $entities = $this->findBy(['login' => $login]);

        $countEntities = count($entities);

        if ($countEntities > 1) {
            throw new Exception\RuntimeException('Найдены пользователи с дублирующимися логинами');
        }

        return 0 === $countEntities ? null : current($entities);
    }
}
