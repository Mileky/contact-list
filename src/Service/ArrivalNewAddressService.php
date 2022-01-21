<?php

namespace DD\ContactList\Service;

use DD\ContactList\Entity\Address;
use DD\ContactList\Entity\AddressRepositoryInterface;
use DD\ContactList\Entity\ContactRepositoryInterface;
use DD\ContactList\Exception\RuntimeException;
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
        $contactId = $addressDto->getIdContact();
        $contactData = $this->contactRepository->findBy(['id_recipient' => $contactId]);

        if (1 !== count($contactData)) {
            throw new RuntimeException("Нельзя добавить адрес контакту с id - '$contactId'. Контакт с таким id не найден");
        }

        $address = new Address(
            $this->addressRepository->nextId(),
            $addressDto->getIdContact(),
            $addressDto->getAddress(),
            $addressDto->getStatus()
        );

        $this->addressRepository->add($address);

        return new ResultRegisteringAddressDto(
            $address->getIdAddress(),
            $address->getIdRecipient(),
            $address->getAddress(),
            $address->getStatus()
        );
    }


}