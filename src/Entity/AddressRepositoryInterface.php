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
     * Получение id для создания новго адреса
     *
     * @return int
     */
    public function nextId(): int;

    /**
     * Добавление нового адреса
     *
     * @param Address $address
     *
     * @return Address
     */
    public function add(Address $address): Address;
}