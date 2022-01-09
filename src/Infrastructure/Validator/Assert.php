<?php

namespace DD\ContactList\Infrastructure\Validator;

/**
 * Коллекции методов реализующих разнообразные проверки в приложении
 */
class Assert
{
    /**
     * Проверка, что заданные элементы массива являются строками
     *
     * @param array $listItemsToCheck  - список элементов для проверки. Ключ - имя проверяемого элемента, значение - текст ошибки
     * @param array $dataForValidation - валидируемые данные
     *
     * @return string|null - текст ошибки или null, если ошибки нет
     */
    public static function arrayElementsIsString(array $listItemsToCheck, array $dataForValidation): ?string
    {
        $result = null;

        foreach ($listItemsToCheck as $paramName => $errMsg) {
            if (array_key_exists($paramName, $dataForValidation) && is_string(
                    $dataForValidation[$paramName]
                ) === false) {
                $result = $errMsg;
                break;
            }
        }

        return $result;
    }

}