<?php

namespace DD\ContactList\Service\AddBlacklistContactService;

class ResultAddBlacklistDto
{
    /**
     * Наличие в черном списке
     *
     * @var bool
     */
    private bool $status;

    /**
     * @param bool $status - Наличие в черном списке
     */
    public function __construct(bool $status)
    {
        $this->status = $status;
    }

    /**
     * @return bool
     */
    public function isStatus(): bool
    {
        return $this->status;
    }
}
