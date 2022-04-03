<?php

namespace DD\ContactList\Config;

use DD\ContactList\Exception;
use DD\ContactList\Infrastructure\HttpApplication\AppConfig as BaseConfig;

/**
 *  Конфиг приложения
 */
class AppConfig extends BaseConfig
{
    /**
     * Uri формы авторизации
     *
     * @var string
     */
    private string $loginUri;

    /**
     * Возвращает uri формы аутентификации
     *
     * @return string
     */
    public function getLoginUri(): string
    {
        return $this->loginUri;
    }

    /**
     * Устанавливает uri формы аутентификации
     *
     * @param string $loginUri
     *
     * @return AppConfig
     */
    protected function setLoginUri(string $loginUri): AppConfig
    {
        $this->loginUri = $loginUri;
        return $this;
    }
}
