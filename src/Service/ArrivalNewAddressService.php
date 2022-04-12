<?php

namespace DD\ContactList\Service;

use DD\ContactList\Entity\AbstractContact;
use DD\ContactList\Entity\Address;
use DD\ContactList\Entity\AddressRepositoryInterface;
use DD\ContactList\Entity\ContactRepositoryInterface;
use DD\ContactList\Service\ArrivalNewAddressService\ContactDto;
use DD\ContactList\Service\ArrivalNewAddressService\NewAddressDto;
use DD\ContactList\Service\ArrivalNewAddressService\ResultRegisteringAddressDto;

class ArrivalNewAddressService
{
    /**
     * Репозиторий для работы с адресами контактов
     *
     * @var AddressRepositoryInterface
     */
    private AddressRepositoryInterface $addressRepository;

    /**
     * Репозиторий для работы с контактами
     *
     * @var ContactRepositoryInterface
     */
    private ContactRepositoryInterface $contactRepository;

    /**
     * @param AddressRepositoryInterface $addressRepository - Репозиторий для работы с адресами контактов
     * @param ContactRepositoryInterface $contactRepository - Репозиторий для работы с контактами
     */
    public function __construct(
        AddressRepositoryInterface $addressRepository,
        ContactRepositoryInterface $contactRepository
    ) {
        $this->addressRepository = $addressRepository;
        $this->contactRepository = $contactRepository;
    }

    /**
     * Добавление нового адреса
     *
     * @param NewAddressDto $addressDto
     *
     * @return ResultRegisteringAddressDto
     */
    public function addAddress(NewAddressDto $addressDto): ResultRegisteringAddressDto
    {
        $contactData = $this->loadContactEntities($addressDto->getIdContacts());

        $address = new Address(
            0,
            $contactData,
            $addressDto->getAddress(),
            new Address\Status($addressDto->getStatus())
        );

        $this->addressRepository->add($address);

        $contactDto = $this->createContactDto($address->getRecipients());

        return new ResultRegisteringAddressDto(
            $address->getId(),
            $contactDto,
            $address->getAddress(),
            $address->getStatus()->getName()
        );
    }

    /**
     * Загрузка сущностей контактов по их id
     *
     * @param array $contactId
     *
     * @return array
     */
    private function loadContactEntities(array $contactId): array
    {
        return $this->contactRepository->findBy(['id' => $contactId]);
    }

    /**
     * Создание ДТО контакта
     *
     * @param array $recipients
     *
     * @return array
     */
    private function createContactDto(array $recipients): array
    {
        return array_map(static function (AbstractContact $contact) {
            return new ContactDto(
                $contact->getId(),
                $contact->getFullName(),
                $contact->getBirthday()->format('d.m.Y'),
                $contact->getProfession()
            );
        }, $recipients);
    }
}
