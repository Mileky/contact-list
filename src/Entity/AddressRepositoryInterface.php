<?php

namespace DD\ContactList\Entity;

/**
 * Интерфейс репозитория адресов контактов
 */
interface AddressRepositoryInterface
{
    /**
     * Поиск адреса по id контакта
     *
     * @param int $contactId
     *
     * @return Address[]
     */
    public function findById(int $contactId): array;

    /**
     * Сохранение адреса в репозиторий
     *
     * @param Address $address
     *
     * @return Address
     */
//    public function save(Address $address): Address;
}