<?php

namespace DD\ContactList\Service\SearchContactService;

/**
 * ДТО родственников
 */
final class KinsfolkDto
{
    /**
     * Тип родства
     *
     * @var string|null
     */
    private ?string $status;

    /**
     * Рингтон
     *
     * @var string|null
     */
    private ?string $ringtone;

    /**
     * Хоткей для набора
     *
     * @var string|null
     */
    private ?string $hotkey;

    /**
     * @param string|null $status   - Тип родства
     * @param string|null $ringtone - Рингтон
     * @param string|null $hotkey   - Хоткей для набора
     */
    public function __construct(?string $status, ?string $ringtone, ?string $hotkey)
    {
        $this->status = $status;
        $this->ringtone = $ringtone;
        $this->hotkey = $hotkey;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getRingtone(): ?string
    {
        return $this->ringtone;
    }

    /**
     * @return string|null
     */
    public function getHotkey(): ?string
    {
        return $this->hotkey;
    }
}
