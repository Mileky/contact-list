<?php

namespace DD\ContactList\Service;

use DD\ContactList\Entity\Address;
use DD\ContactList\Entity\AddressRepositoryInterface;
use DD\ContactList\Entity\ContactList;
use DD\ContactList\Entity\ContactListRepositoryInterface;
use DD\ContactList\Service\AddBlacklistContactService\Exception\ContactNotFoundException;
use DD\ContactList\Service\AddBlacklistContactService\ResultAddBlacklistDto;

class AddBlacklistContactService
{

    /**
     * Репозиторий для работы с списком контактов
     *
     * @var ContactListRepositoryInterface
     */
    private ContactListRepositoryInterface $contactListRepository;

    /**
     * @param ContactListRepositoryInterface $contactListRepository - Репозиторий для работы с списком контактов
     */
    public function __construct(ContactListRepositoryInterface $contactListRepository)
    {
        $this->contactListRepository = $contactListRepository;
    }


    public function addBlacklist(int $contactId): ResultAddBlacklistDto
    {
        $contactList = $this->contactListRepository->findById($contactId);

        if (1 !== count($contactList) && count($contactList) !== 2) {
            throw new ContactNotFoundException("Не удалось найти контакт с id = '$contactId' для занесения в ЧС");
        }

        /** @var ContactList $contactListEntity */
        $contactListEntity = current($contactList);

        $contactListEntity->moveToIgnore();

        $this->contactListRepository->save($contactListEntity);

        return new ResultAddBlacklistDto($contactListEntity->isBlacklist());
    }
}