<?php

namespace DD\ContactList\Infrastructure\Auth;

use DD\ContactList\Infrastructure\Http\HttpResponse;
use DD\ContactList\Infrastructure\Http\ServerResponseFactory;
use DD\ContactList\Infrastructure\Session\SessionInterface;
use DD\ContactList\Infrastructure\Uri\Uri;

/**
 * Поставщик услуги аутентификации
 */
class HttpAuthProvider
{
    /**
     * Контейнер работы с сессиями
     *
     * @var SessionInterface
     */
    private SessionInterface $session;

    /**
     * Uri для открытия формы аутентификации
     *
     * @var Uri
     */
    private Uri $loginUri;

    /**
     * Ключ, по которому в сессии хранятся идентификатор пользователя
     */
    private const USER_ID = 'user_id';

    /**
     * Хранилище данных о пользователе
     *
     * @var UserDataStorageInterface
     */
    private UserDataStorageInterface $userDataStorage;

    /**
     * @param UserDataStorageInterface $userDataStorage - Хранилище данных о пользователе
     * @param SessionInterface $session                 - Контейнер работы с сессиями
     * @param Uri $loginUri                             - Uri для открытия формы аутентификации
     */
    public function __construct(
        UserDataStorageInterface $userDataStorage,
        SessionInterface $session,
        Uri $loginUri
    ) {
        $this->userDataStorage = $userDataStorage;
        $this->session = $session;
        $this->loginUri = $loginUri;
    }

    /**
     * Проводит аутентификацию
     *
     * @param string $login    - логин пользователя
     * @param string $password - пароль пользователя
     *
     * @return bool - определяет прошла ли аутентификация успешно
     */
    public function auth(string $login, string $password): bool
    {
        $isAuth = false;
        $user = $this->userDataStorage->findUserByLogin($login);

        if (null !== $user && password_verify($password, $user->getPassword())) {
            $this->session->set(self::USER_ID, $login);
            $isAuth = true;
        }

        return $isAuth;
    }

    /**
     * Проверка, что пользователь аутентифицирован
     *
     * @return bool
     */
    public function isAuth(): bool
    {
        return $this->session->has(self::USER_ID);
    }

    /**
     * Возвращает Uri страницы для ввода логина и пароля
     *
     * @return Uri
     */
    private function getLoginUri(): Uri
    {
        return $this->loginUri;
    }

    /**
     * Запуск процесса аутентификации
     *
     * @param Uri $successUri - адрес для редиректа в случае успешного ввода логина и пароля
     *
     * @return HttpResponse - http ответ приводящий к открытию формы аутентификации
     */
    public function doAuth(Uri $successUri): HttpResponse
    {
        $loginUri = $this->getLoginUri();
        $loginQueryStr = $loginUri->getQuery();

        $loginQuery = [];
        parse_str($loginQueryStr, $loginQuery);
        $loginQuery['redirect'] = (string)$successUri;

        $uri = new Uri(
            $loginUri->getScheme(),
            $loginUri->getUserInfo(),
            $loginUri->getHost(),
            $loginUri->getPort(),
            $loginUri->getPath(),
            http_build_query($loginQuery),
            $loginUri->getFragment()
        );

        return ServerResponseFactory::redirect($uri);

    }


}