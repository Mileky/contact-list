<?php

namespace DD\ContactList\Controller;

use DD\ContactList\Entity\Customer;
use DD\ContactList\Infrastructure\Controller\ControllerInterface;
use DD\ContactList\Infrastructure\DataLoader\JsonDataLoader;
use DD\ContactList\Infrastructure\Http\HttpResponse;
use DD\ContactList\Infrastructure\Http\ServerRequest;
use DD\ContactList\Infrastructure\Http\ServerResponseFactory;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;

use DD\ContactList\Infrastructure\Validator\Assert;
use JsonException;


/**
 * Контроллер для работы с Клиентами
 */
class GetCustomersCollectionController implements ControllerInterface
{
    /**
     * Путь до файла с Клиентами
     *
     * @var string
     */
    private string $pathToCustomers;

    /**
     * Логер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger
     * @param string $pathToCustomers
     */
    public function __construct(LoggerInterface $logger, string $pathToCustomers)
    {
        $this->logger = $logger;
        $this->pathToCustomers = $pathToCustomers;

    }

    /**
     * Определяет http код
     *
     * @param array $foundCustomers
     *
     * @return int
     */
    protected function buildHttpCode(array $foundCustomers): int
    {
        return 200;
    }

    /**
     * Подготавливает данные для ответа
     *
     * @param array $foundCustomers
     *
     * @return array
     */
    protected function buildResult(array $foundCustomers)
    {
        return $foundCustomers;
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

        $params = array_merge($serverRequest->getQueryParams(), $serverRequest->getAttributes());
        return Assert::arrayElementsIsString($paramValidations, $params);
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
        $searchCriteria = array_merge($serverRequest->getQueryParams(), $serverRequest->getAttributes());
        foreach ($customers as $customer) {
            if (array_key_exists('id_recipient', $searchCriteria)) {
                $customerMeetSearchCriteria = $searchCriteria['id_recipient'] === $customer['id_recipient'];
            } elseif (array_key_exists('full_name', $searchCriteria)) {
                $customerMeetSearchCriteria = $searchCriteria['full_name'] === $customer['full_name'];
            } elseif (array_key_exists('birthday', $searchCriteria)) {
                $customerMeetSearchCriteria = $searchCriteria['birthday'] === $customer['birthday'];
            } elseif (array_key_exists('profession', $searchCriteria)) {
                $customerMeetSearchCriteria = $searchCriteria['profession'] === $customer['profession'];
            } elseif (array_key_exists('contract_number', $searchCriteria)) {
                $customerMeetSearchCriteria = $searchCriteria['contract_number'] === $customer['contract_number'];
            } elseif (array_key_exists('average_transaction_amount', $searchCriteria)) {
                $customerMeetSearchCriteria = $searchCriteria['average_transaction_amount'] === $customer['average_transaction_amount'];
            } elseif (array_key_exists('discount', $searchCriteria)) {
                $customerMeetSearchCriteria = $searchCriteria['discount'] === $customer['discount'];
            } elseif (array_key_exists('time_to_call', $searchCriteria)) {
                $customerMeetSearchCriteria = $searchCriteria['time_to_call'] === $customer['time_to_call'];
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
        return (new JsonDataLoader())->loadData($this->pathToCustomers);
    }

    /**
     * обработка запроса поиска Клиентов
     *
     * @param ServerRequest $serverServerRequest - объект серверного запроса
     *
     * @return HttpResponse
     * @throws JsonException
     */
    public function __invoke(ServerRequest $serverServerRequest): HttpResponse
    {
        $this->logger->log('dispatch "customers" url');

        $resultOfParamValidation = $this->validateQueryParams($serverServerRequest);

        if (null === $resultOfParamValidation) {
            $customers = $this->loadData();
            $foundCustomers = $this->searchCustomersInData($customers, $serverServerRequest);
            $httpCode = $this->buildHttpCode($foundCustomers);
            $result = $this->buildResult($foundCustomers);
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