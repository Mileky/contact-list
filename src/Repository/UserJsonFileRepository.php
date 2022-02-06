<?php

namespace DD\ContactList\Repository;

use DD\ContactList\Entity\User;
use DD\ContactList\Entity\UserRepositoryInterface;
use DD\ContactList\Infrastructure\Auth\UserDataStorageInterface;
use DD\ContactList\Infrastructure\DataLoader\DataLoaderInterface;
use DD\ContactList\Exception;
use DD\ContactList\Repository\UserRepository\UserDataProvider;

class UserJsonFileRepository implements UserRepositoryInterface, UserDataStorageInterface
{
    /**
     * Путь до файла с данными о пользователях
     *
     * @var string
     */
    private string $pathToUsers;

    /**
     * Загрузчик данных
     *
     * @var DataLoaderInterface
     */
    private DataLoaderInterface $dataLoader;

    /**
     * Загруженные данные о пользователях
     *
     * @var array|null
     */
    private ?array $data = null;

    /**
     * @param string              $pathToUsers - Путь до файла с данными о пользователях
     * @param DataLoaderInterface $dataLoader  - Загрузчик данных
     */
    public function __construct(string $pathToUsers, DataLoaderInterface $dataLoader)
    {
        $this->pathToUsers = $pathToUsers;
        $this->dataLoader = $dataLoader;
    }


    /**
     * @inheritDoc
     */
    public function findBy(array $criteria): array
    {
        $dataItems = $this->loadData();
        $foundEntities = [];

        foreach ($dataItems as $user) {
            if (false === is_array($user)) {
                throw new Exception\RuntimeException('Данные о пользователе должны быть массивом');
            }

            if (array_key_exists('login', $criteria)) {
                $userMeetSearchCriteria = $criteria['login'] === $user['login'];
            } else {
                $userMeetSearchCriteria = true;
            }

            if ($userMeetSearchCriteria && array_key_exists('id', $criteria)) {
                $userMeetSearchCriteria = $criteria['id'] === $user['id'];
            }

            if ($userMeetSearchCriteria) {
                $entity = $this->createUser($user);
                $foundEntities[] = $entity;
            }
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
            throw new Exception\RuntimeException('Найдены пользователи с дублирющимися логинами');
        }

        return 0 === $countEntities ? null : current($entities);
    }

    /**
     * Загружает данные о пользователях
     *
     * @return array
     */
    private function loadData(): array
    {
        if (null === $this->data) {
            $this->data = $this->dataLoader->loadData($this->pathToUsers);
            if (false === is_array($this->data)) {
                throw new Exception\RuntimeException('Данные о пользователях должны быть массивом');
            }
        }

        return $this->data;
    }

    /**
     * Логика создания сущности User
     *
     * @param array $user - данные для создания пользователя
     *
     * @return UserDataProvider - сущность Пользователя
     */
    private function createUser(array $user): UserDataProvider
    {
        $this->validateUserItem($user);

        return new UserDataProvider(
            $user['id'],
            $user['login'],
            $user['password']
        );
    }

    /**
     * Валидирует данные о пользователе
     *
     * @param array $user
     *
     * @return void
     */
    private function validateUserItem(array $user): void
    {
        if (false === array_key_exists('id', $user)) {
            throw new Exception\RuntimeException('отсутсвутет Id пользователя');
        }
        if (false === array_key_exists('login', $user)) {
            throw new Exception\RuntimeException('отсутсвутет логин пользователя');
        }
        if (false === array_key_exists('password', $user)) {
            throw new Exception\RuntimeException('отсутсвутет пароль пользователя');
        }

        if (false === is_int($user['id'])) {
            throw new Exception\RuntimeException('ID пользователя должен быть целым числом');
        }
        if (false === is_string($user['login'])) {
            throw new Exception\RuntimeException('Логин пользователя должен быть строкой');
        }
        if (false === is_string($user['password'])) {
            throw new Exception\RuntimeException('Пароль пользователя должен быть строкой');
        }
    }
}
