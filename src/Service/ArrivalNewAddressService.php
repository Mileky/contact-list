<?php

namespace DD\ContactList\Service;

use DD\ContactList\Entity\Address;
use DD\ContactList\Entity\AddressRepositoryInterface;
use DD\ContactList\Entity\ContactRepositoryInterface;
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
            $this->addressRepository->nextId(),
            $contactData,
            $addressDto->getAddress(),
            new Address\Status($addressDto->getStatus())
        );

        $this->addressRepository->add($address);

        return new ResultRegisteringAddressDto(
            $address->getId(),
            $address->getRecipients(),
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
        return $this->contactRepository->findBy(['list_id' => $contactId]);
    }
}
