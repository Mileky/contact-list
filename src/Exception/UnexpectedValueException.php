<?php

namespace DD\ContactList\Exception;

use DD\ContactList\Infrastructure\Exception as BaseException;

/**
 * Исключение бросается, если значения не совпадают с набором значений.
 * Обычно это происходит когда функция вызывает другую функцию и ожидает, что возвращаемое значение определено
 */
class UnexpectedValueException extends BaseException\UnexpectedValueException implements ExceptionInterface
{
}
