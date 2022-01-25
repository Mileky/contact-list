<?php

namespace DD\ContactList\Infrastructure\Logger;

use DateTimeImmutable;
use Throwable;
use DD\ContactList\Infrastructure\Exception;

class Logger implements LoggerInterface
{

    /**
     * Адаптер для записи лога в конкретное хранилище
     *
     * @var AdapterInterface
     */
    private AdapterInterface $adapter;

    /**
     * Разрешенные уровни логирования
     */
    private const ALLOW_LEVEL = [
        LogLevel::EMERGENCY => LogLevel::EMERGENCY,
        LogLevel::ALERT => LogLevel::ALERT,
        LogLevel::CRITICAL => LogLevel::CRITICAL,
        LogLevel::ERROR => LogLevel::ERROR,
        LogLevel::WARNING => LogLevel::WARNING,
        LogLevel::NOTICE => LogLevel::NOTICE,
        LogLevel::INFO => LogLevel::INFO,
        LogLevel::DEBUG => LogLevel::DEBUG
    ];

    /**
     * @param AdapterInterface $adapter - Адаптер для записи лога в конкретное хранилище
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }


    /**
     * @inheritDoc
     */
    public function emergency(string $message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function alert(string $message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function critical(string $message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function error(string $message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function warning(string $message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function notice(string $message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function info(string $message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function debug(string $message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function log(string $level, string $message, array $context = []): void
    {
        try {
            $this->validateLevel($level);

            $formatMsg = $this->formatMsg($message, $context);

            $this->adapter->write($level, $formatMsg);
        } catch (Throwable $e) {
        }

    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @return string
     */
    private function formatMsg(string $message, array $context): string
    {
        $date = $this->formatDate();
        $ip = $this->formatIp();
        $contextStr = $this->formatContext($context);

        return $ip . ' - ' . '[' . $date . ']' . $message . ' ' . $contextStr;
    }

    /**
     * Валидация корректности уровня логирования
     *
     * @param string $level - уровень логирования
     *
     * @return void
     */
    private function validateLevel(string $level): void
    {
        if (false === array_key_exists($level, self::ALLOW_LEVEL)) {
            throw new Exception\RuntimeException('Неподдерживаемый уровень логирования ' . $level);
        }
    }

    /**
     * Получение даты и времени когда произошло логирование
     *
     * @return string
     */
    private function formatDate(): string
    {
        return (new DateTimeImmutable())->format('d/M/Y:H:i:s O');
    }

    /**
     * Возвращает строку с информацией о клиенте вызвавшим событие
     *
     * @return string
     */
    private function formatIp(): string
    {
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } elseif ('cli' === PHP_SAPI) {
            $ip = 'console';
        } else {
            $ip = 'unknown';
        }

        return $ip;

    }

    /**
     * Преобразование
     *
     * @param array $context
     *
     * @return string
     */
    private function formatContext(array $context): string
    {
        if (count($context) > 0) {
            $contextStr = print_r($context, true);
        } else {
            $contextStr = '';
        }

        return $contextStr;
    }

}