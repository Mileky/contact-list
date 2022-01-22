<?php

namespace DD\ContactList\Controller;

use DD\ContactList\Exception\RuntimeException;
use DD\ContactList\Infrastructure\Controller\ControllerInterface;
use DD\ContactList\Infrastructure\Http\HttpResponse;
use DD\ContactList\Infrastructure\Http\ServerRequest;
use DD\ContactList\Infrastructure\Http\ServerResponseFactory;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;
use DD\ContactList\Infrastructure\ViewTemplate\ViewTemplateInterface;
use DD\ContactList\Service\ArrivalNewAddressService;
use DD\ContactList\Service\ArrivalNewAddressService\NewAddressDto;
use DD\ContactList\Service\SearchAddressService;
use DD\ContactList\Service\SearchAddressService\SearchAddressCriteria;
use DD\ContactList\Service\SearchContactService;

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
     * @param LoggerInterface $logger                            - Логгер
     * @param ArrivalNewAddressService $arrivalNewAddressService - Сервис добавления нового адреса у контакта
     * @param SearchContactService $searchContactService         - Сервис поиска контактов
     * @param ViewTemplateInterface $viewTemplate                - Шаблонизатор для рендеринга html
     * @param SearchAddressService $addressService               - Сервис поиска адресов
     */
    public function
    __construct(
        LoggerInterface $logger,
        ArrivalNewAddressService $arrivalNewAddressService,
        SearchContactService $searchContactService,
        ViewTemplateInterface $viewTemplate,
        SearchAddressService $addressService
    ) {
        $this->logger = $logger;
        $this->arrivalNewAddressService = $arrivalNewAddressService;
        $this->searchContactService = $searchContactService;
        $this->viewTemplate = $viewTemplate;
        $this->addressService = $addressService;
    }


    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequest $serverRequest): HttpResponse
    {
        $this->logger->log('run AddressAdministrationController:__invoke');

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

        $html = $this->viewTemplate->render(__DIR__ . '/../../templates/address.Administration.phtml', $context);

        return ServerResponseFactory::createHtmlResponse(200, $html);
    }

    /**
     * Результат добавления новых адресов
     *
     * @param ServerRequest $serverRequest
     *
     * @return array - данные о ошибках при добавлении адреса
     */
    private function addAddress(ServerRequest $serverRequest): array
    {
        $dataToCreate = [];
        parse_str($serverRequest->getBody(), $dataToCreate);

        $result = [];

        $result['addressValidationError'] = $this->validateAddressData($dataToCreate);

        if (0 === count($result['addressValidationError'])) {
            $this->createAddress($dataToCreate);
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
        $requestDto = new NewAddressDto(
            (int)$dataToCreate['id_recipient'],
            $dataToCreate['address'],
            $dataToCreate['status'],
        );

       $this->arrivalNewAddressService->addAddress($requestDto);
    }
}