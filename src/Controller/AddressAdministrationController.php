<?php

namespace DD\ContactList\Controller;

use DD\ContactList\Exception\RuntimeException;
use DD\ContactList\Infrastructure\Auth\HttpAuthProvider;
use DD\ContactList\Infrastructure\Controller\ControllerInterface;
use DD\ContactList\Infrastructure\Http\ServerResponseFactory;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use DD\ContactList\Infrastructure\ViewTemplate\ViewTemplateInterface;
use DD\ContactList\Service\ArrivalNewAddressService;
use DD\ContactList\Service\ArrivalNewAddressService\NewAddressDto;
use DD\ContactList\Service\SearchAddressService;
use DD\ContactList\Service\SearchAddressService\SearchAddressCriteria;
use DD\ContactList\Service\SearchContactService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class AddressAdministrationController implements ControllerInterface
{
    /**
     * Логгер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Сервис добавления нового адреса у контакта
     *
     * @var ArrivalNewAddressService
     */
    private ArrivalNewAddressService $arrivalNewAddressService;

    /**
     * Сервис поиска контактов
     *
     * @var SearchContactService
     */
    private SearchContactService $searchContactService;

    /**
     * Сервис поиска адресов
     *
     * @var SearchAddressService
     */
    private SearchAddressService $addressService;

    /**
     * Шаблонизатор для рендеринга html
     *
     * @var ViewTemplateInterface
     */
    private ViewTemplateInterface $viewTemplate;

    /**
     * Поставщик услуг аутентификации
     *
     * @var HttpAuthProvider
     */
    private HttpAuthProvider $httpAuthProvider;

    /**
     * Фабрика создания серверного ответа
     *
     * @var ServerResponseFactory
     */
    private ServerResponseFactory $serverResponseFactory;

    /**
     * Менеджер сущностей
     *
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;


    /**
     * @param LoggerInterface $logger                            - Логгер
     * @param ArrivalNewAddressService $arrivalNewAddressService - Сервис добавления нового адреса у контакта
     * @param SearchContactService $searchContactService         - Сервис поиска контактов
     * @param ViewTemplateInterface $viewTemplate                - Шаблонизатор для рендеринга html
     * @param SearchAddressService $addressService               - Сервис поиска адресов
     * @param HttpAuthProvider $httpAuthProvider                 - Поставщик услуг аутентификации
     * @param ServerResponseFactory $serverResponseFactory       - Фабрика создания серверного ответа
     * @param EntityManagerInterface $em                    - Менеджер сущностей
     */
    public function __construct(
        LoggerInterface $logger,
        ArrivalNewAddressService $arrivalNewAddressService,
        SearchContactService $searchContactService,
        ViewTemplateInterface $viewTemplate,
        SearchAddressService $addressService,
        HttpAuthProvider $httpAuthProvider,
        ServerResponseFactory $serverResponseFactory,
        EntityManagerInterface $em
    ) {
        $this->logger = $logger;
        $this->arrivalNewAddressService = $arrivalNewAddressService;
        $this->searchContactService = $searchContactService;
        $this->viewTemplate = $viewTemplate;
        $this->addressService = $addressService;
        $this->httpAuthProvider = $httpAuthProvider;
        $this->serverResponseFactory = $serverResponseFactory;
        $this->em = $em;
    }


    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        try {
            if (false === $this->httpAuthProvider->isAuth()) {
                return $this->httpAuthProvider->doAuth($serverRequest->getUri());
            }


            $this->logger->info('run AddressAdministrationController:__invoke');

            $resultAddAddress = [];
            if ('POST' === $serverRequest->getMethod()) {
                $resultAddAddress = $this->addAddress($serverRequest);
            }

            $dtoAddressCollection = $this->addressService->search(new SearchAddressCriteria());
            $dtoContactsCollection = $this->searchContactService->search(
                new SearchContactService\SearchContactServiceCriteria()
            );

            $viewData = [
                'addresses' => $dtoAddressCollection,
                'contacts' => $dtoContactsCollection
            ];

            $context = array_merge($viewData, $resultAddAddress);
            $template = 'address.Administration.twig';

            $httpCode = 200;
        } catch (Throwable $e) {
            $httpCode = 500;
            $template = 'errors.twig';
            $context = [
                'errors' => [
                    $e->getMessage()
                ]
            ];
        }

        $html = $this->viewTemplate->render($template, $context);

        return $this->serverResponseFactory->createHtmlResponse($httpCode, $html);
    }

    /**
     * Результат добавления новых адресов
     *
     * @param ServerRequestInterface $serverRequest
     *
     * @return array - данные об ошибках при добавлении адреса
     */
    private function addAddress(ServerRequestInterface $serverRequest): array
    {
        $dataToCreate = [];
        parse_str($serverRequest->getBody(), $dataToCreate);

        $result = [];

        $result['addressValidationError'] = $this->validateAddressData($dataToCreate);

        if (0 === count($result['addressValidationError'])) {
            $this->createAddress($dataToCreate);
        } else {
            $result['addressData'] = $dataToCreate;
        }

        return $result;
    }

    /**
     * Валидация данных от пользователя для добавления адреса
     *
     * @param array $dataToCreate
     *
     * @return void
     */
    private function validateAddressData(array $dataToCreate): array
    {
        $errs = [];

        if (false === array_key_exists('address', $dataToCreate)) {
            throw new RuntimeException('Нет данных о адресе');
        }

        if (false === is_string($dataToCreate['address'])) {
            throw new RuntimeException('Данные о адресе должны быть строкой');
        }

        $addressLength = strlen(trim($dataToCreate['address']));

        if ($addressLength > 250) {
            $errs[] = 'Адрес не может быть длиннее 250 символов';
        } elseif ($addressLength === 0) {
            $errs[] = 'Адрес не может быть пустым';
        } elseif (1 !== preg_match('/[^А-Яа-я]*, [1-9]+\/?([0-9]*)?/', $dataToCreate['address'])) {
            $errs[] = 'Адрес имеет неверный формат';
        }

        return $errs;
    }

    /**
     * Добавление нового адреса
     *
     * @param array $dataToCreate
     *
     * @return void
     */
    private function createAddress(array $dataToCreate): void
    {
        try {
            $this->em->beginTransaction();

            $requestDto = new NewAddressDto(
                $dataToCreate['id_recipient'],
                $dataToCreate['address'],
                $dataToCreate['status'],
            );

            $this->arrivalNewAddressService->addAddress($requestDto);

            $this->em->flush();
            $this->em->commit();
        } catch (Throwable $e) {
            $this->em->rollback();

            throw new RuntimeException(
                'Ошибка при добавлении нового адреса: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
}
