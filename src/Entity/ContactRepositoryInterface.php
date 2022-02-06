<?php

namespace DD\ContactList\Entity;

/**
 * Интерфейс репозитория контактов
 */
interface ContactRepositoryInterface
{
    /**
     * Поиск сущностей по заданному критерию
     *
     * @param array $criteria
     *
     * @return AbstractContact[]
     */
    public function findBy(array $criteria): array;

    /**
     * Поиск сущностей по категориям
     *
     * @param array $criteria
     *
     * @return array
     */
    public function findByCategory(array $criteria): array;
}
