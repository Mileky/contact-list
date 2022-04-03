<?php

namespace DD\ContactList\Repository\UserRepository;

use DD\ContactList\Entity\User;
use DD\ContactList\Infrastructure\Auth\UserDataProviderInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Поставщик данных о логине/пароле пользователя
 *
 * @ORM\Entity(repositoryClass=\DD\ContactList\Repository\UserDoctrineRepository::class)
 * @ORM\Table(
 *     name="users",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="users_login_unq", columns={"login"})
 *     }
 * )
 */
class UserDataProvider extends User implements UserDataProviderInterface
{
}
