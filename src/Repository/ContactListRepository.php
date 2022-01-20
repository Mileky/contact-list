<?php

namespace DD\ContactList\Repository;

use DD\ContactList\Entity\ContactList;
use DD\ContactList\Entity\ContactListRepositoryInterface;
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
     * Загрузчик данных
     *
     * @var DataLoaderInterface
     */
    private DataLoaderInterface $dataLoader;

    /**
     * @param string $pathToContactList
     * @param DataLoaderInterface $dataLoader
     */
    public function __construct(string $pathToContactList, DataLoaderInterface $dataLoader)
    {
        $this->pathToContactList = $pathToContactList;
        $this->dataLoader = $dataLoader;
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

        $foundContactList = [];
        foreach ($contactListData as $contactList) {
            $meetSearchCriteria = $contactId === $contactList['id_recipient'];

            if ($meetSearchCriteria) {
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
        $id = $contactList->getIdRecipient();

        $contactListId = $this->idRecipient;

        if (false === array_key_exists($id, $contactListId)) {
            throw new RuntimeException("Текстовой документ с ID = $id не найден в хранилище");
        }

        return $contactListId[$id];
    }

    private function buildJsonDataContactList(ContactList $contactList): array
    {
        return [
            'id_entry' => $contactList->getIdEntry(),
            'id_recipient' => $contactList->getIdRecipient(),
            'blacklist' => $contactList->isBlacklist()
        ];
    }
}