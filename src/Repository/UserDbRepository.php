<?php

namespace DD\ContactList\Repository;

use DD\ContactList\Entity\UserRepositoryInterface;
use DD\ContactList\Infrastructure\Auth\UserDataStorageInterface;
use DD\ContactList\Exception;
use DD\ContactList\Infrastructure\Db\ConnectionInterface;
use DD\ContactList\Repository\UserRepository\UserDataProvider;

/**
 * Репозиторий для работы с сущностью User через БД
 */
class UserDbRepository implements UserRepositoryInterface, UserDataStorageInterface
{
    /**
     * Компонент отвечающий за соединение с БД
     *
     * @var ConnectionInterface
     */
    private ConnectionInterface $connection;

    /**
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }


    /**
     * @inheritDoc
     */
    public function findBy(array $criteria): array
    {
        $sql = <<<EOF
SELECT id, login, password FROM users 
EOF;

        $whereParts = [];
        foreach ($criteria as $fieldName => $fieldValue) {
            $whereParts[] = "$fieldName = '$fieldValue'";
        }
        if (count($whereParts) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $whereParts);
        }
        $dataFromDb = $this->connection->query($sql)->fetchAll();

        $foundEntities = [];
        foreach ($dataFromDb as $item) {
            $foundEntities[] = new UserDataProvider($item['id'], $item['login'], $item['password']);
        }

        return $foundEntities;
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
