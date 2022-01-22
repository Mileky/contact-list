<?php

namespace DD\ContactList\Infrastructure\HttpApplication;

class AppConfig implements AppConfigInterface
{
    /**
     * Сокрытие сообщений об ошибках
     * @var bool
     */
    private bool $hideErrorMessage;

    /**
     * @inheritDoc
     */
    public function isHideErrorMessage(): bool
    {
        return $this->hideErrorMessage;
    }

    /**
     * Устанавливает флаг, что нужно скрывать сообщения об ошибках
     *
     * @param bool $hideErrorMessage
     *
     * @return AppConfig
     */
    private function setHideErrorMessage(bool $hideErrorMessage): AppConfig
    {
        $this->hideErrorMessage = $hideErrorMessage;
        return $this;
    }

    /**
     * Создает конфиг из массива
     *
     * @param array $config
     *
     * @return AppConfigInterface
     * @uses \DD\ContactList\Infrastructure\HttpApplication\AppConfig::setHideErrorMessage()
     */
    public static function createFromArray(array $config): AppConfigInterface
    {
        $appConfigObj = new static();

        foreach ($config as $key => $value) {
            if (property_exists($appConfigObj, $key)) {
                $setter = 'set' . ucfirst($key);
                $appConfigObj->{$setter}($value);
            }
        }
        return $appConfigObj;
    }
}