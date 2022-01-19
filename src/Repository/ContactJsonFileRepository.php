<?php

namespace DD\ContactList\Repository;

use DD\ContactList\Entity\Colleague;
use DD\ContactList\Entity\ContactRepositoryInterface;
use DD\ContactList\Entity\Customer;
use DD\ContactList\Entity\Kinsfolk;
use DD\ContactList\Entity\Recipient;
use DD\ContactList\Infrastructure\DataLoader\DataLoaderInterface;
use DD\ContactList\ValueObject\Messenger;
use DD\ContactList\Exception;

class ContactJsonFileRepository implements ContactRepositoryInterface
{

    /**
     * Загрузчик данных
     *
     * @var DataLoaderInterface
     */
    private DataLoaderInterface $dataLoader;


    /**
     * Путь до данных с Знакомыми
     *
     * @var string
     */
    private string $pathToRecipients;

    /**
     * Путь до данных с Клиентами
     *
     * @var string
     */
    private string $pathToCustomers;

    /**
     * Путь до данных с Родственниками
     *
     * @var string
     */
    private string $pathToKinsfolk;

    /**
     * Путь до данных с Коллегами
     *
     * @var string
     */
    private string $pathToColleagues;

    /**
     * @param DataLoaderInterface $dataLoader - Загрузчик данных
     * @param string $pathToRecipients        - Путь до данных с Знакомыми
     * @param string $pathToCustomers         - Путь до данных с Клиентами
     * @param string $pathToKinsfolk          - Путь до данных с Родственниками
     * @param string $pathToColleagues        - Путь до данных с Коллегами
     */
    public function __construct(
        DataLoaderInterface $dataLoader,
        string $pathToRecipients,
        string $pathToCustomers,
        string $pathToKinsfolk,
        string $pathToColleagues
    ) {
        $this->dataLoader = $dataLoader;
        $this->pathToRecipients = $pathToRecipients;
        $this->pathToCustomers = $pathToCustomers;
        $this->pathToKinsfolk = $pathToKinsfolk;
        $this->pathToColleagues = $pathToColleagues;
    }


    /**
     * Логика создания коллекции объектов значений - "мессенджер"
     *
     * @param $recipient
     *
     * @return array
     */
    private function createMessengers($recipient): array
    {
        if (false === array_key_exists('messengers', $recipient)) {
            throw new Exception\InvalidDataStructureException('Нет данных о мессенджере');
        }

        if (false === is_array($recipient['messengers'])) {
            throw new Exception\InvalidDataStructureException('Данные о мессенджере имеют неверный формат');
        }

        $messengers = [];

        foreach ($recipient['messengers'] as $messengerData) {
            $messengers[] = $this->createMessenger($messengerData);
        }

        return $messengers;
    }

    /**
     * Создание объекта Мессенджер
     *
     * @param $messengerData
     *
     * @return Messenger
     */
    private function createMessenger($messengerData): Messenger
    {
        if (false === is_array($messengerData)) {
            throw new Exception\InvalidDataStructureException('Данные о мессенджере имеют неверный формат');
        }

        if (false === array_key_exists('typeMessenger', $messengerData)) {
            throw new Exception\InvalidDataStructureException('Отсутствуеют данные о имени мессенджера');
        }
        if (false === is_string($messengerData['typeMessenger'])) {
            throw new Exception\InvalidDataStructureException('Данные о имени мессенджера имеют неверный формат');
        }

        if (false === array_key_exists('username', $messengerData)) {
            throw new Exception\InvalidDataStructureException('Отсутствуют данные о имени пользователя');
        }

        if (false === is_string($messengerData['username'])) {
            throw new Exception\InvalidDataStructureException('Данные о имени пользователя имеют неверный формат');
        }

        return new Messenger($messengerData['typeMessenger'], $messengerData['username']);
    }

    /**
     * Загрузка данных о Знакомых
     *
     * @return array
     */
    private function loadContactsData(): array
    {
        $recipients = $this->dataLoader->loadData($this->pathToRecipients);
        $kinsfolk = $this->dataLoader->loadData($this->pathToKinsfolk);
        $customers = $this->dataLoader->loadData($this->pathToCustomers);
        $colleague = $this->dataLoader->loadData($this->pathToColleagues);

        return [
            'recipients' => $recipients,
            'kinsfolk' => $kinsfolk,
            'customers' => $customers,
            'colleagues' => $colleague,
        ];
    }

    private function contactFactory(array $contactData)
    {
        $contactData['messengers'] = $this->createMessengers($contactData);

        if (array_key_exists('status', $contactData)) {
            $objContact = Kinsfolk::createFromArray($contactData);
        } elseif (array_key_exists('contract_number', $contactData)) {
            $objContact = Customer::createFromArray($contactData);
        } elseif (array_key_exists('department', $contactData)) {
            $objContact = Colleague::createFromArray($contactData);
        } else {
            $objContact = Recipient::createFromArray($contactData);
        }

        return $objContact;
    }


    /**
     * @inheritDoc
     */
    public function findBy(array $criteria): array
    {
        $loadedContactsData = $this->loadContactsData();


        $foundContacts = [];
        foreach ($loadedContactsData as $contactsData) {
            foreach ($contactsData as $contactData) {
                $contactMeetSearchCriteria = $this->CriteriaCheck($criteria, $contactData);

                if ($contactMeetSearchCriteria) {
                    $foundContacts[] = $this->contactFactory($contactData);
                }
            }
        }

        return $foundContacts;
    }

    /**
     * Проверяет сущность на соответсвие критериям поиска
     *
     * @param array $searchCriteria - криетрии поиска
     * @param array $entityData     - коллекция сущностей, которые нужно проверить
     *
     * @return bool
     */
    private function CriteriaCheck(array $searchCriteria, array $entityData): bool
    {
        $result = false;
        foreach ($searchCriteria as $key => $value) {
            if (isset($entityData[$key]) && (string)$value === (string)$entityData[$key]) {
                $result = true;
            }
        }
        if (count($searchCriteria) === 0) {
            $result = true;
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function findByCategory(array $criteria): array
    {
        $contactsData = $this->loadContactsData();

        $foundContacts = [];

        foreach ($contactsData[$criteria['category']] as $contactData) {
            $foundContacts[] = $this->contactFactory($contactData);
        }

        return $foundContacts;
    }
}