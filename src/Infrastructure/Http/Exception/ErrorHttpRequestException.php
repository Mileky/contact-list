<?php

namespace DD\ContactList\Infrastructure\Http\Exception;

use DD\ContactList\Exception\RuntimeException;

/**
 * Исключение бросается в случае, если не удалось создать объект http запроса
 */
class ErrorHttpRequestException extends RuntimeException
{

}