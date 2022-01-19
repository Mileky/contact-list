<?php

namespace DD\ContactList\ConsoleCommand;

use DD\ContactList\Infrastructure\Console\CommandInterface;
use DD\ContactList\Infrastructure\Console\Output\OutputInterface;
use DD\ContactList\Service\SearchContactService;
use JsonException;

class FindContacts implements CommandInterface
{

    /**
     * Компонент отвечающий за вывод данных в консоль
     *
     * @var OutputInterface
     */
    private OutputInterface $output;

    /**
     * Сервис поиска контактов
     *
     * @var SearchContactService
     */
    private SearchContactService $searchContactService;

    /**
     * @param OutputInterface $output - Компонент отвечающий за вывод данных в консоль
     * @param SearchContactService $searchContactService - Сервис поиска контактов
     */
    public function __construct(OutputInterface $output,
        SearchContactService $searchContactService
    )
    {
        $this->output = $output;
        $this->searchContactService = $searchContactService;
    }


    /**
     * @inheritDoc
     */
    public static function getShortOptions(): string
    {
        return 'n:';
    }

    /**
     * @inheritDoc
     */
    public static function getLongOptions(): array
    {
        return [
            'category:',
            'id_recipient:',
            'full_name:',
            'birthday:',
            'profession:',
            'contract_number:',
            'average_transaction_amount:',
            'discount:',
            'time_to_call:',
            'status:',
            'ringtone:',
            'hotkey:',
            'department:',
            'position:',
            'room_number:',
        ];
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function __invoke(array $params): void
    {
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
        $jsonContactData = $this->buildJsonData($foundContactsDto);
        $this->output->print(json_encode($jsonContactData, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Создание json формата
     *
     * @param array $foundContactsDto - ДТО контакта
     *
     * @return array
     */
    private function buildJsonData(array $foundContactsDto): array
    {
        $result = [];
        foreach ($foundContactsDto as $foundContactDto) {
            $result[] = $this->serializeTextDocument($foundContactDto);
        }
        return $result;
    }

    /**
     * Сериализация данных о Контакте для результата поиска
     *
     * @param SearchContactService\ContactDto $contactDto - найденный контакт
     *
     * @return array
     */
    private function serializeTextDocument(SearchContactService\ContactDto $contactDto): array
    {
        $jsonData = [
            'id_recipient' => $contactDto->getId(),
            'full_name' => $contactDto->getFullName(),
            'birthday' => $contactDto->getBirthday(),
            'profession' => $contactDto->getProfession(),
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