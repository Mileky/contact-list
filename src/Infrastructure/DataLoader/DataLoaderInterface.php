<?php

namespace DD\ContactList\Infrastructure\DataLoader;

/**
 * Интерфейс загрузчика данных из файла
 */
interface DataLoaderInterface
{
    /**
     * Загрузка и десериализация данных
     *
     * @param string $sourceName - имя загружаемого файла
     *
     * @return array
     */
    public function loadData(string $sourceName): array;

}