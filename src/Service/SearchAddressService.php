<?php

namespace DD\ContactList\Service;

use DD\ContactList\Entity\Address;
use DD\ContactList\Entity\AddressRepositoryInterface;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;
use DD\ContactList\Service\SearchAddressService\AddressDto;
use DD\ContactList\Service\SearchAddressService\SearchAddressCriteria;

/**
 * Сервис поиска адресов
 */
class SearchAddressService
{
    /**
     * Логгер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Репозиторий для работы с адресами контактов
     *
     * @var AddressRepositoryInterface
     */
    private AddressRepositoryInterface $addressRepository;

    /**
     * @param LoggerInterface $logger
     * @param AddressRepositoryInterface $addressRepository
     */
    public function __construct(LoggerInterface $logger, AddressRepositoryInterface $addressRepository)
    {
        $this->logger = $logger;
        $this->addressRepository = $addressRepository;
    }

    public function search(SearchAddressCriteria $searchAddressCriteria): array
    {
        $criteria = $this->searchCriteriaToArray($searchAddressCriteria);
        $addressCollection = $this->addressRepository->findBy($criteria);

        $addressDto = [];
        foreach ($addressCollection as $address) {
            $addressDto[] = $this->createDto($address);
        }

        $this->log('found address: ' . count($addressCollection));

        return $addressDto;
    }

    /**
     * Преобразование критериев поиска в массив
     *
     * @param SearchAddressCriteria $searchAddressCriteria
     *
     * @return array
     */
    private function searchCriteriaToArray(SearchAddressCriteria $searchAddressCriteria): array
    {
        $criteriaForRepository = [
            'id_address' => $searchAddressCriteria->getIdAddress(),
            'id_recipient' => $searchAddressCriteria->getIdRecipient(),
            'address' => $searchAddressCriteria->getAddress(),
            'status' => $searchAddressCriteria->getStatus()
        ];

        return array_filter($criteriaForRepository, static function ($v): bool {
            return null !== $v;
        });
    }

    /**
     * Создание объекта ДТО адреса
     *
     * @param Address $address
     *
     * @return AddressDto
     */
    private function createDto(Address $address): AddressDto
    {
        return new AddressDto(
            $address->getIdAddress(),
            $address->getIdRecipient()->getIdRecipient(),
            $address->getAddress(),
            $address->getStatus()
        );
    }

    /**
     * Логирование
     *
     * @param string $msg - логируемое сообщение
     *
     * @return void
     */
    private function log(string $msg): void
    {
        $this->logger->debug($msg);
    }

}