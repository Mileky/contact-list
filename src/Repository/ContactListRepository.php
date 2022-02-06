<?php

namespace DD\ContactList\Repository;

use DD\ContactList\Entity\AbstractContact;
use DD\ContactList\Entity\ContactList;
use DD\ContactList\Entity\ContactListRepositoryInterface;
use DD\ContactList\Entity\ContactRepositoryInterface;
use DD\ContactList\Exception\RuntimeException;
use DD\ContactList\Infrastructure\DataLoader\DataLoaderInterface;

/**
 * Репозиторий контактного листа
 */
class ContactListRepository implements ContactListRepositoryInterface
{
    /**
     * Путь до контактного листа
     *
     * @var string
     */
    private string $pathToContactList;

    /**
     * Репозиторий для работы с контактами
     *
     * @var ContactRepositoryInterface
     */
    private ContactRepositoryInterface $contactRepository;

    /**
     * Загруженные данные контактного листа
     *
     * @var array|null
     */
    private ?array $contactListData = null;

    /**
     * Сопоставление id контакта с номером элемента в $contactListData
     *
     * @var array|null
     */
    private ?array $idRecipient;

    /**
     * Данные о контакте
     *
     * @var AbstractContact[]|null
     */
    private ?array $contactIdToInfo = null;

    /**
     * Загрузчик данных
     *
     * @var DataLoaderInterface
     */
    private DataLoaderInterface $dataLoader;

    /**
     * @param string                     $pathToContactList
     * @param DataLoaderInterface        $dataLoader
     * @param ContactRepositoryInterface $contactRepository
     */
    public function __construct(
        string $pathToContactList,
        DataLoaderInterface $dataLoader,
        ContactRepositoryInterface $contactRepository
    ) {
        $this->pathToContactList = $pathToContactList;
        $this->dataLoader = $dataLoader;
        $this->contactRepository = $contactRepository;
    }

    /**
     * Загрузка данных о контактах
     *
     * @return array
     */
    private function loadContactsData(): array
    {
        if (null === $this->contactIdToInfo) {
            $contacts = $this->contactRepository->findBy();
            $contactIdToInfo = [];

            foreach ($contacts as $contact) {
                $contactIdToInfo[$contact->getIdRecipient()] = $contact;
            }
            $this->contactIdToInfo = $contactIdToInfo;
        }

        return $this->contactIdToInfo;
    }

    /**
     * Загрузка данных о контакном листе
     *
     * @return array
     */
    private function loadData(): array
    {
        if (null === $this->contactListData) {
            $this->contactListData = $this->dataLoader->loadData($this->pathToContactList);

            $this->idRecipient = array_combine(
                array_map(static function (array $v) {
                    return $v['id_recipient'];
                }, $this->contactListData),
                array_keys($this->contactListData)
            );
        }

        return $this->contactListData;
    }

    /**
     * @inheritDoc
     */
    public function findById(int $contactId): array
    {
        $contactListData = $this->loadData();
        $contactIdToInfo = $this->loadContactsData();

        $foundContactList = [];
        foreach ($contactListData as $contactList) {
            $meetSearchCriteria = $contactId === $contactList['id_recipient'];

            if ($meetSearchCriteria) {
                $contactList['id_recipient'] = $contactIdToInfo[$contactList['id_recipient']];
                $foundContactList[] = ContactList::createFromArray($contactList);
            }
        }

        return $foundContactList;
    }

    /**
     * @inheritDoc
     */
    public function save(ContactList $contactList): ContactList
    {
        $this->loadData();

        $data = $this->contactListData;
        $itemIndex = $this->getItemIndex($contactList);

        $item = $this->buildJsonDataContactList($contactList);

        $data[$itemIndex] = $item;

        $file = $this->pathToContactList;

        $jsonStr = json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($file, $jsonStr);

        return $contactList;
    }

    private function getItemIndex(ContactList $contactList): int
    {
        $id = $contactList->getIdRecipient()->getIdRecipient();

        $contactListId = $this->idRecipient;

        if (false === array_key_exists($id, $contactListId)) {
            throw new RuntimeException("Контакт с ID = $id не найден в хранилище");
        }

        return $contactListId[$id];
    }

    private function buildJsonDataContactList(ContactList $contactList): array
    {
        return [
            'id_entry'     => $contactList->getIdEntry(),
            'id_recipient' => $contactList->getIdRecipient()->getIdRecipient(),
            'blacklist'    => $contactList->isBlacklist()
        ];
    }
}
