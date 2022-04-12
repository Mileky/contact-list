<?php

namespace DD\ContactList\Entity;

use DateTimeImmutable;
use DD\ContactList\Exception;
use Doctrine\ORM\Mapping as ORM;

/**
 * Родственник
 *
 * @ORM\Entity
 * @ORM\Table(
 *     name="contacts_kinsfolk",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="contacts_kinsfolk_hotkey_unq", columns={"hotkey"})},
 *     indexes={
 *          @ORM\Index(name="contacts_kinsfolk_ringtone_idx", columns={"ringtone"}),
 *          @ORM\Index(name="contacts_kinsfolk_status_idx", columns={"status"})
 *     }
 * )
 */
class Kinsfolk extends AbstractContact
{
    /**
     * Статус родственника
     *
     * @ORM\Column(name="status", type="string", length=30, nullable=false)
     *
     * @var string
     */
    private string $status;

    /**
     * Рингтон стоящий на родственнике
     *
     * @ORM\Column(name="ringtone", type="string", length=30, nullable=true)
     *
     * @var string
     */
    private string $ringtone;

    /**
     * Горячая клавиша для звонка родственнику
     *
     * @ORM\Column(name="hotkey", type="integer", nullable=true)
     *
     * @var string
     */
    private string $hotkey;

    /**
     * @param int $id_recipient           - id Получателя
     * @param string $full_name           - Полное имя получателя
     * @param DateTimeImmutable $birthday - Дата рождения получателя
     * @param string $profession          - Профессия получателя
     * @param string $status              - Статус родственника
     * @param string $ringtone            - Рингтон стоящий на родственнике
     * @param string $hotkey              - Горячая клавиша для звонка родственнику
     */
    public function __construct(
        int $id_recipient,
        string $full_name,
        DateTimeImmutable $birthday,
        string $profession,
        array $messengers,
        string $status,
        string $ringtone,
        string $hotkey
    ) {
        parent::__construct($id_recipient, $full_name, $birthday, $profession, $messengers);
        $this->status = $status;
        $this->ringtone = $ringtone;
        $this->hotkey = $hotkey;
    }


    /**
     * Возвращает статус родственника
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Устанавливает статус родственника
     *
     * @param string $status
     *
     * @return Kinsfolk
     */
    public function setStatus(string $status): Kinsfolk
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Возвращает рингтон родственника
     *
     * @return string
     */
    public function getRingtone(): string
    {
        return $this->ringtone;
    }

    /**
     * Устанавливает рингтон
     *
     * @param string $ringtone
     *
     * @return Kinsfolk
     */
    public function setRingtone(string $ringtone): Kinsfolk
    {
        $this->ringtone = $ringtone;
        return $this;
    }

    /**
     * Возвращает горячую клавишу
     *
     * @return string
     */
    public function getHotkey(): string
    {
        return $this->hotkey;
    }

    /**
     * Устанавливает горячую клавишу
     *
     * @param string $hotkey
     *
     * @return Kinsfolk
     */
    public function setHotkey(string $hotkey): Kinsfolk
    {
        $this->hotkey = $hotkey;
        return $this;
    }
}
