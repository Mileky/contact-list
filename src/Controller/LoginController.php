<?php

namespace DD\ContactList\Controller;

use DD\ContactList\Infrastructure\Auth\HttpAuthProvider;
use DD\ContactList\Infrastructure\Controller\ControllerInterface;
use DD\ContactList\Infrastructure\Http\HttpResponse;
use DD\ContactList\Infrastructure\Http\ServerRequest;
use DD\ContactList\Infrastructure\Http\ServerResponseFactory;
use DD\ContactList\Infrastructure\Uri\Uri;
use DD\ContactList\Infrastructure\ViewTemplate\ViewTemplateInterface;
use DD\ContactList\Exception;
use Throwable;

class LoginController implements ControllerInterface
{

    /**
     * Шаблонизатор
     *
     * @var ViewTemplateInterface
     */
    private ViewTemplateInterface $viewTemplate;

    /**
     * Сервис услуги аутентификации
     *
     * @var HttpAuthProvider
     */
    private HttpAuthProvider $httpAuthProvider;

    /**
     * @param ViewTemplateInterface $viewTemplate
     * @param HttpAuthProvider      $httpAuthProvider
     */
    public function __construct(
        ViewTemplateInterface $viewTemplate,
        HttpAuthProvider $httpAuthProvider
    ) {
        $this->viewTemplate = $viewTemplate;
        $this->httpAuthProvider = $httpAuthProvider;
    }


    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequest $serverRequest): HttpResponse
    {
        try {
            $response = $this->doLogin($serverRequest);
        } catch (Throwable $e) {
            $response = $this->buildErrorResponse($e);
        }

        return $response;
    }

    /**
     * Создает HTTP ответ для ошибки
     *
     * @param Throwable $e
     *
     * @return HttpResponse
     */
    private function buildErrorResponse(Throwable $e): HttpResponse
    {
        $httpCode = 500;
        $context = [
            'errors' => [
                $e->getMessage()
            ]
        ];

        $html = $this->viewTemplate->render(
            __DIR__ . '/../../templates/errors.phtml',
            $context
        );

        return ServerResponseFactory::createHtmlResponse($httpCode, $html);
    }

    /**
     * Реализация процесса аутентификации
     *
     * @param ServerRequest $serverRequest
     *
     * @return HttpResponse
     */
    private function doLogin(ServerRequest $serverRequest): HttpResponse
    {
        $response = null;
        $context = [];
        if ('POST' === $serverRequest->getMethod()) {
            $authData = [];
            parse_str($serverRequest->getBody(), $authData);

            $this->validateAuthData($authData);

            if ($this->isAuth($authData['login'], $authData['password'])) {
                $queryParams = $serverRequest->getQueryParams();
                $redirect = array_key_exists('redirect', $queryParams)
                    ? Uri::createFromString($queryParams['redirect'])
                    : Uri::createFromString('/');
                $response = ServerResponseFactory::redirect($redirect);
            } else {
                $context['errMsg'] = 'Логин и пароль не подходят';
            }
        }

        if (null === $response) {
            $html = $this->viewTemplate->render(__DIR__ . '/../../templates/login.phtml', $context);
            $response = ServerResponseFactory::createHtmlResponse(200, $html);
        }

        return $response;
    }

    /**
     * Логика валидации данных формы аутентификации
     *
     * @param array $authData
     *
     * @return void
     */
    private function validateAuthData(array $authData): void
    {
        if (false === array_key_exists('login', $authData)) {
            throw new Exception\RuntimeException('Отсутствует логин');
        }
        if (false === is_string($authData['login'])) {
            throw new Exception\RuntimeException('Логин имеет неверный формат');
        }
        if (false === array_key_exists('password', $authData)) {
            throw new Exception\RuntimeException('Отсутствует пароль');
        }
        if (false === is_string($authData['password'])) {
            throw new Exception\RuntimeException('Пароль имеет неверный формат');
        }
    }

    /**
     * Проводит аутентификацию пользователя
     *
     * @param string $login    - логин пользователя
     * @param string $password - пароль пользователя
     *
     * @return bool
     */
    private function isAuth(string $login, string $password): bool
    {
        return $this->httpAuthProvider->auth($login, $password);
    }

}