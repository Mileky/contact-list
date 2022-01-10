<?php

namespace DD\ContactList\Controller;

use DD\ContactList\Entity\Customer;
use DD\ContactList\Infrastructure\AppConfig;
use DD\ContactList\Infrastructure\Controller\ControllerInterface;
use DD\ContactList\Infrastructure\DataLoader\JsonDataLoader;
use DD\ContactList\Infrastructure\DI\ContainerInterface;
use DD\ContactList\Infrastructure\DI\ServiceLocator;
use DD\ContactList\Infrastructure\Http\HttpResponse;
use DD\ContactList\Infrastructure\Http\ServerRequest;
use DD\ContactList\Infrastructure\Http\ServerResponseFactory;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;

use DD\ContactList\Infrastructure\Validator\Assert;
use JsonException;


/**
 * Поиск клиентов
 */
final class FindCustomers implements ControllerInterface
{
    /**
     * Логер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Конфиг приложения
     *
     * @var AppConfig
     */
    private AppConfig $appConfig;

    /**
     * @param AppConfig $appConfig
     * @param LoggerInterface $logger
     */
    public function __construct(AppConfig $appConfig, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->appConfig = $appConfig;

    }


    /**
     * Валидирует параметры запроса
     *
     * @param ServerRequest $serverRequest - объект серверного запроса
     *
     * @return string|null - строка с ошибкой или null, если ошибки нет
     */
    private function validateQueryParams(ServerRequest $serverRequest): ?string
    {
        $paramValidations = [
            'id_recipient' => 'incorrect id_recipient',
            'full_name' => 'incorrect full_name',
            'birthday' => 'incorrect birthday',
            'profession' => 'incorrect profession',
            'contract_number' => ' incorrect contract_number',
            'average_transaction_amount' => 'incorrect average_transaction_amount',
            'discount' => 'incorrect discount',
            'time_to_call' => 'incorrect time_to_call'
        ];

        $queryParams = $serverRequest->getQueryParams();

        return Assert::arrayElementsIsString($paramValidations, $queryParams);
    }

    /**
     * Логика поиска Клиентов
     *
     * @param array $customers -  - массив с данными из файла с Клиентами
     * @param ServerRequest $serverRequest
     *
     * @return Customer[]
     */
    private function searchCustomersInData(
        array $customers,
        ServerRequest $serverRequest
    ): array {
        $foundCustomers = [];
        $requestParams = $serverRequest->getQueryParams();

        foreach ($customers as $customer) {
            if (array_key_exists('id_recipient', $requestParams)) {
                $customerMeetSearchCriteria = $requestParams['id_recipient'] === $customer['id_recipient'];
            } elseif (array_key_exists('full_name', $requestParams)) {
                $customerMeetSearchCriteria = $requestParams['full_name'] === $customer['full_name'];
            } elseif (array_key_exists('birthday', $requestParams)) {
                $customerMeetSearchCriteria = $requestParams['birthday'] === $customer['birthday'];
            } elseif (array_key_exists('profession', $requestParams)) {
                $customerMeetSearchCriteria = $requestParams['profession'] === $customer['profession'];
            } elseif (array_key_exists('contract_number', $requestParams)) {
                $customerMeetSearchCriteria = $requestParams['contract_number'] === $customer['contract_number'];
            } elseif (array_key_exists('average_transaction_amount', $requestParams)) {
                $customerMeetSearchCriteria = $requestParams['average_transaction_amount'] === $customer['average_transaction_amount'];
            } elseif (array_key_exists('discount', $requestParams)) {
                $customerMeetSearchCriteria = $requestParams['discount'] === $customer['discount'];
            } elseif (array_key_exists('time_to_call', $requestParams)) {
                $customerMeetSearchCriteria = $requestParams['time_to_call'] === $customer['time_to_call'];
            } else {
                $customerMeetSearchCriteria = true;
            }
            if ($customerMeetSearchCriteria) {
                $foundCustomers[] = Customer::createFromArray($customer);
            }
        }
        $this->logger->log('found customers: ' . count($foundCustomers));

        return $foundCustomers;
    }

    /**
     * Загрузка данных о Клиентах
     *
     * @throws JsonException
     */
    private function loadData(): array
    {
        return (new JsonDataLoader())->loadData($this->appConfig->getPathToCustomers());
    }

    /**
     * обработка запроса поиска Клиентов
     *
     * @param ServerRequest $serverRequest - объект серверного запроса
     *
     * @return HttpResponse
     * @throws JsonException
     */
    public function __invoke(ServerRequest $serverRequest): HttpResponse
    {
        $this->logger->log('dispatch "customers" url');

        $resultOfParamValidation = $this->validateQueryParams($serverRequest);

        if (null === $resultOfParamValidation) {
            $customers = $this->loadData();
            $httpCode = 200;
            $result = $this->searchCustomersInData($customers, $serverRequest);
        } else {
            $httpCode = 500;
            $result = [
                'status' => 'fail',
                'message' => $resultOfParamValidation
            ];
        }
        return ServerResponseFactory::createJsonResponse($httpCode, $result);
    }
}