<?php

namespace DD\ContactList\Infrastructure\Exception;

/**
 * Это исключение создается, если значение не соответствует определенной допустимой области данных
 */
class DomainException extends \DomainException implements ExceptionInterface
{

}