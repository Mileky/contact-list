<?php

namespace DD\ContactList\Entity;

interface ContactListRepositoryInterface
{
    /**
     * Поиск контакта в контактном листе
     *
     * @param int $contactId
     *
     * @return ContactList[]
     */
    public function findById(int $contactId): array;

    /**
     * Сохранение
     *
     * @param ContactList $contactList
     *
     * @return ContactList
     */
    public function save(ContactList $contactList): ContactList;
}
