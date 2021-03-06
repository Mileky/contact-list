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
     * @param array $criteria - критерии поиска адресов
     *
     * @return Address[]
     */
    public function findBy(array $criteria): array;

    /**
     * Добавление нового адреса
     *
     * @param Address $address
     *
     * @return Address
     */
    public function add(Address $address): Address;
}
