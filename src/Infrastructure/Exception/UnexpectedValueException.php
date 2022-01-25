<?php

namespace DD\ContactList\Infrastructure\Exception;

/**
 * Исключение бросается, если значения не совпадают с набором значений.
 * Обычно это происходит когда функция вызывает другую функцию и ожидает, что возвращаемое значение определено
 */
class UnexpectedValueException extends \UnexpectedValueException implements ExceptionInterface
{

}