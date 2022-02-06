<?php

namespace DD\ContactList\Exception;

use DD\ContactList\Infrastructure\Exception as BaseException;

/**
 * Это исключение создается, если значение не соответствует определенной допустимой области данных
 */
class DomainException extends BaseException\DomainException implements ExceptionInterface
{
}
