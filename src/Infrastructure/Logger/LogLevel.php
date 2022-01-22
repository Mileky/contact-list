<?php

namespace DD\ContactList\Infrastructure\Logger;

/**
 * Уровни ошибок
 */
class LogLevel
{
    /**
     * Уровень подробной информации для отладки
     */
    public const DEBUG = 'debug';

    /**
     * Уровень интересных событий (когда нужно записать то, что имеет ценность для анализа)
     */
    public const INFO = 'info';

    /**
     * Уровень существенных событий, но это еще не ошибки
     */
    public const NOTICE = 'notice';

    /**
     * Уровень исключительных случаев, но не ошибки
     */
    public const WARNING = 'warning';

    /**
     * Уровень ошибок исполнения, не требующая сиюминутного вмешательства
     */
    public const ERROR = 'error';

    /**
     * Уровень критического состояния - компонент системы недоступен, неожиданное исключение
     */
    public const CRITICAL = 'critical';

    /**
     * Уровень, когда действие требует безотлагательного вмешательства
     */
    public const ALERT = 'alert';

    /**
     * Уровень, когда система полностью не работает
     */
    public const EMERGENCY = 'emergency';
}