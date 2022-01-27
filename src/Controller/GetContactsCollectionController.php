<?php

namespace DD\ContactList\Controller;

use DD\ContactList\Infrastructure\Controller\ControllerInterface;
use DD\ContactList\Infrastructure\Http\HttpResponse;
use DD\ContactList\Infrastructure\Http\ServerRequest;
use DD\ContactList\Infrastructure\Http\ServerResponseFactory;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;
use DD\ContactList\Infrastructure\Validator\Assert;
use DD\ContactList\Service\SearchContactService;

/**
 * Контроллер для работы с Знакомыми
 */
class GetContactsCollectionController implements ControllerInterface
{

    /**
     * Сервис поиска контактов
     *
     * @var SearchContactService
     */
    private SearchContactService $searchContactService;

    /**
     * Логгер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param LoggerInterface      $logger               - логгер
     * @param SearchContactService $searchContactService - сервис поиска контактов
     */
    public function __construct(
        LoggerInterface $logger,
        SearchContactService $searchContactService
    ) {
        $this->logger = $logger;
        $this->searchContactService = $searchContactService;
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
            'id_recipient'               => 'incorrect id_recipient',
            'full_name'                  => 'incorrect full_name',
            'birthday'                   => 'incorrect birthday',
            'profession'                 => 'incorrect profession',
            'contract_number'            => 'incorrect contract_number',
            'average_transaction_amount' => 'incorrect average_transaction_amount',
            'discount'                   => 'incorrect discount',
            'time_to_call'               => 'incorrect time_to_call',
            'status'                     => 'incorrect status',
            'ringtone'                   => 'incorrect ringtone',
            'hotkey'                     => 'incorrect hotkey',
            'department'                 => 'incorrect department',
            'position'                   => 'incorrect position',
            'room_number'                => 'incorrect room_number',

        ];

        $queryParams = $serverRequest->getQueryParams();

        return Assert::arrayElementsIsString($paramValidations, $queryParams);
    }

    /**
     * Определяет http код
     *
     * @param array $foundRecipients
     *
     * @return int
     */
    protected function buildHttpCode(array $foundRecipients): int
    {
        return 200;
    }

    /**
     * Подготавливает данные для ответа
     *
     * @param array $foundContacts
     *
     * @return array
     */
    protected function buildResult(array $foundContacts): array
    {
        $result = [];
        foreach ($foundContacts as $foundContact) {
            $result[] = $this->serializeContact($foundContact);
        }
        return $result;
    }

    /**
     * Обработка запроса поиска контактов
     *
     * @param ServerRequest $serverRequest
     *
     * @return HttpResponse
     */
    public function __invoke(ServerRequest $serverRequest): HttpResponse
    {
        $this->logger->info('dispatch "contacts" url');

        $resultOfParamValidation = $this->validateQueryParams($serverRequest);

        if (null === $resultOfParamValidation) {
            $params = array_merge($serverRequest->getQueryParams(), $serverRequest->getAttributes());
            $params['category'] = substr($serverRequest->getRequestTarget(), 1);

            $foundContactsDto = $this->searchContactService->search(
                (new SearchContactService\SearchContactServiceCriteria())
                    ->setCategory($params['category'] ?? null)
                    ->setId($params['id_recipient'] ?? null)
                    ->setFullName($params['full_name'] ?? null)
                    ->setBirthday($params['birthday'] ?? null)
                    ->setProfession($params['profession'] ?? null)
                    ->setStatus($params['status'] ?? null)
                    ->setRingtone($params['ringtone'] ?? null)
                    ->setHotkey($params['hotkey'] ?? null)
                    ->setContractNumber($params['contract_number'] ?? null)
                    ->setAverageTransactionAmount($params['average_transaction_amount'] ?? null)
                    ->setDiscount($params['discount'] ?? null)
                    ->setTimeToCall($params['time_to_call'] ?? null)
                    ->setDepartment($params['department'] ?? null)
                    ->setPosition($params['position'] ?? null)
                    ->setRoomNumber($params['room_number'] ?? null)
            );

            $httpCode = $this->buildHttpCode($foundContactsDto);
            $result = $this->buildResult($foundContactsDto);
        } else {
            $httpCode = 500;
            $result = [
                'status'  => 'fail',
                'message' => $resultOfParamValidation
            ];
        }
        return ServerResponseFactory::createJsonResponse($httpCode, $result);
    }

    /**
     * Сериализация данных о Контакте для результата поиска
     *
     * @param SearchContactService\ContactDto $contactDto - найденный контакт
     *
     * @return array
     */
    protected function serializeContact(SearchContactService\ContactDto $contactDto): array
    {
        $jsonData = [
            'id_recipient' => $contactDto->getId(),
            'full_name'    => $contactDto->getFullName(),
            'birthday'     => $contactDto->getBirthday(),
            'profession'   => $contactDto->getProfession(),
        ];

        if ($contactDto->getType() === SearchContactService\ContactDto::TYPE_COLLEAGUE) {
            $jsonData['department'] = $contactDto->getColleagueData()->getDepartment();
            $jsonData['position'] = $contactDto->getColleagueData()->getPosition();
            $jsonData['room_number'] = $contactDto->getColleagueData()->getRoomNumber();
        } elseif ($contactDto->getType() === SearchContactService\ContactDto::TYPE_CUSTOMER) {
            $jsonData['contract_number'] = $contactDto->getCustomerData()->getContractNumber();
            $jsonData['average_transaction_amount'] = $contactDto->getCustomerData()->getAverageTransactionAmount();
            $jsonData['discount'] = $contactDto->getCustomerData()->getDiscount();
            $jsonData['time_to_call'] = $contactDto->getCustomerData()->getTimeToCall();
        } elseif ($contactDto->getType() === SearchContactService\ContactDto::TYPE_KINSFOLK) {
            $jsonData['status'] = $contactDto->getKinsfolkData()->getStatus();
            $jsonData['ringtone'] = $contactDto->getKinsfolkData()->getRingtone();
            $jsonData['hotkey'] = $contactDto->getKinsfolkData()->getHotkey();
        }

        return $jsonData;
    }


}