<?php

namespace DD\ContactList\Infrastructure\Logger;

/**
 *  Интерфейс логирования
 */
interface LoggerInterface
{

    /**
     * Когда система полностью не работает
     *
     * @param string $message - текст, который записываем в лог
     * @param array $context  - дополнительный контекст (данные специфичные для логируемого события)
     *
     * @return void
     */
    public function emergency(string $message, array $context = []): void;

    /**
     * Когда действие требует безотлагательного вмешательства
     *
     * @param string $message - текст, который записываем в лог
     * @param array $context  - дополнительный контекст (данные специфичные для логируемого события)
     *
     * @return void
     */
    public function alert(string $message, array $context = []): void;

    /**
     * Критического состояния - компонент системы недоступен, неожиданное исключение
     *
     * @param string $message - текст, который записываем в лог
     * @param array $context  - дополнительный контекст (данные специфичные для логируемого события)
     *
     * @return void
     */
    public function critical(string $message, array $context = []): void;

    /**
     * Уровень ошибок исполнения, не требующая сиюминутного вмешательства
     *
     * @param string $message - текст, который записываем в лог
     * @param array $context  - дополнительный контекст (данные специфичные для логируемого события)
     *
     * @return void
     */
    public function error(string $message, array $context = []): void;

    /**
     * Уровень исключительных случаев, но не ошибки
     *
     * @param string $message - текст, который записываем в лог
     * @param array $context  - дополнительный контекст (данные специфичные для логируемого события)
     *
     * @return void
     */
    public function warning(string $message, array $context = []): void;

    /**
     * Уровень существенных событий, но это еще не ошибки
     *
     * @param string $message - текст, который записываем в лог
     * @param array $context  - дополнительный контекст (данные специфичные для логируемого события)
     *
     * @return void
     */
    public function notice(string $message, array $context = []): void;

    /**
     * Уровень интересных событий (когда нужно записать то, что имеет ценность для анализа)
     *
     * @param string $message - текст, который записываем в лог
     * @param array $context  - дополнительный контекст (данные специфичные для логируемого события)
     *
     * @return void
     */
    public function info(string $message, array $context = []): void;

    /**
     * Уровень подробной информации для отладки
     *
     * @param string $message - текст, который записываем в лог
     * @param array $context  - дополнительный контекст (данные специфичные для логируемого события)
     *
     * @return void
     */
    public function debug(string $message, array $context = []): void;

    /**
     * апись в логи сообщение
     *
     * @param string $level   - уровень логирования
     * @param string $message - текст, который записываем в лог
     * @param array $context  - дополнительный контекст (данные специфичные для логируемого события)
     *
     * @return void
     */
    public function log(string $level, string $message, array $context = []): void;
}