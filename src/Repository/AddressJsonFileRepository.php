<?php

namespace DD\ContactList\Repository;

use DD\ContactList\Entity\Address;
use DD\ContactList\Entity\AddressRepositoryInterface;
use DD\ContactList\Infrastructure\DataLoader\DataLoaderInterface;

class AddressJsonFileRepository implements AddressRepositoryInterface
{
    /**
     * Путь до данных с адресами контактов
     *
     * @var string
     */
    private string $pathToContactList;

    /**
     * Загрузчик данных
     *
     * @var DataLoaderInterface
     */
    private DataLoaderInterface $dataLoader;

    /**
     * @param string $pathToContactList       - Путь до контактного листа
     * @param DataLoaderInterface $dataLoader - Загрузчик данных
     */
    public function __construct(string $pathToContactList, DataLoaderInterface $dataLoader)
    {
        $this->pathToContactList = $pathToContactList;
        $this->dataLoader = $dataLoader;
    }

    /**
     * Загрузка данных о контактном листе
     *
     * @return array
     */
    private function loadData(): array
    {
        return [];
    }


    /**
     * @inheritDoc
     */
    public function findById(int $contactId): array
    {
        return [];
    }

//    /**
//     * @inheritDoc
//     */
//    public function save(Address $address): Address
//    {
//    }
}