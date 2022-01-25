<?php

namespace DD\ContactList\Exception;

use DD\ContactList\Infrastructure\Exception as BaseException;

/**
 * Исключение бросается в случае, если не удалось создать конфиг приложения
 */
class ErrorCreateAppConfigException extends BaseException\ErrorCreateAppConfigException implements ExceptionInterface
{

}