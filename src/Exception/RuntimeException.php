<?php

namespace DD\ContactList\Exception;

use DD\ContactList\Infrastructure\Exception as BaseException;

/**
 * Исключение бросается в результате ошибок, которые возникли во время выполнения
 */
class RuntimeException extends BaseException\RuntimeException implements ExceptionInterface
{
}
