<?php

namespace DD\ContactList\Exception;

use DD\ContactList\Infrastructure\Exception as BaseException;

/**
 * Исключение выбрасывается в случае, если данные, с которыми работает приложение имеет не валидные значения
 */
class InvalidDataStructureException extends BaseException\InvalidDataStructureException implements ExceptionInterface
{
}
