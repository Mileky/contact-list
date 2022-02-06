<?php

namespace DD\ContactList\Repository\UserRepository;

use DD\ContactList\Entity\User;
use DD\ContactList\Infrastructure\Auth\UserDataProviderInterface;

/**
 * Поставщик данных о логине/пароле пользователя
 */
class UserDataProvider extends User implements UserDataProviderInterface
{
}
