<?php

namespace DD\ContactList\Controller;

use DD\ContactList\Exception;
use DD\ContactList\Infrastructure\Auth\HttpAuthProvider;
use DD\ContactList\Infrastructure\Controller\ControllerInterface;
use DD\ContactList\Infrastructure\Http\ServerResponseFactory;
use DD\ContactList\Infrastructure\ViewTemplate\ViewTemplateInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriFactoryInterface;
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
     * Фабрика для создания uri
     *
     * @var UriFactoryInterface
     */
    private UriFactoryInterface $uriFactory;

    /**
     * Фабрика создания серверного ответа
     *
     * @var ServerResponseFactory
     */
    private ServerResponseFactory $serverResponseFactory;

    /**
     * @param ViewTemplateInterface $viewTemplate          - Шаблонизатор
     * @param HttpAuthProvider $httpAuthProvider           - Сервис услуги аутентификации
     * @param UriFactoryInterface $uriFactory              - Фабрика для создания uri
     * @param ServerResponseFactory $serverResponseFactory - Фабрика создания серверного ответа
     */
    public function __construct(
        ViewTemplateInterface $viewTemplate,
        HttpAuthProvider $httpAuthProvider,
        UriFactoryInterface $uriFactory,
        ServerResponseFactory $serverResponseFactory
    ) {
        $this->viewTemplate = $viewTemplate;
        $this->httpAuthProvider = $httpAuthProvider;
        $this->serverResponseFactory = $serverResponseFactory;
        $this->uriFactory = $uriFactory;
    }


    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
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
     * @return ResponseInterface
     */
    private function buildErrorResponse(Throwable $e): ResponseInterface
    {
        $httpCode = 500;
        $context = [
            'errors' => [
                $e->getMessage()
            ]
        ];

        $html = $this->viewTemplate->render(
            'errors.twig',
            $context
        );

        return $this->serverResponseFactory->createHtmlResponse($httpCode, $html);
    }

    /**
     * Реализация процесса аутентификации
     *
     * @param ServerRequestInterface $serverRequest
     *
     * @return ResponseInterface
     */
    private function doLogin(ServerRequestInterface $serverRequest): ResponseInterface
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
                    ? $this->uriFactory->createUri($queryParams['redirect'])
                    : $this->uriFactory->createUri('/');
                $response = $this->serverResponseFactory->redirect($redirect);
            } else {
                $context['errMsg'] = 'Логин и пароль не подходят';
            }
        }

        if (null === $response) {
            $html = $this->viewTemplate->render('login.twig', $context);
            $response = $this->serverResponseFactory->createHtmlResponse(200, $html);
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
