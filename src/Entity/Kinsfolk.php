<?php

namespace DD\ContactList\Entity;

use DD\ContactList\Exception;

/**
 * Родственник
 */
final class Kinsfolk extends Recipient
{
    /**
     * Статус родственника
     *
     * @var string
     */
    private string $status;

    /**
     * Рингтон стоящий на родственнике
     *
     * @var string
     */
    private string $ringtone;

    /**
     * Горячая клавиша для звонка родственнику
     *
     * @var string
     */
    private string $hotkey;

    /**
     * @param int $id_recipient  - id Получателя
     * @param string $full_name  - Полное имя получателя
     * @param string $birthday   - Дата рождения получателя
     * @param string $profession - Профессия получателя
     * @param string $status     - Статус родственника
     * @param string $ringtone   - Рингтон стоящий на родственнике
     * @param string $hotkey     - Горячая клавиша для звонка родственнику
     */
    public function __construct(
        int $id_recipient,
        string $full_name,
        string $birthday,
        string $profession,
        string $status,
        string $ringtone,
        string $hotkey
    ) {
        parent::__construct($id_recipient, $full_name, $birthday, $profession);
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

    public function jsonSerialize(): array
    {
        $jsonData = parent::jsonSerialize();
        $jsonData['status'] = $this->status;
        $jsonData['ringtone'] = $this->ringtone;
        $jsonData['hotkey'] = $this->hotkey;
        return $jsonData;
    }

    /**
     * Создание сущности "Родственник" из массива
     *
     * @param array $data
     *
     * @return Kinsfolk
     */
    public static function createFromArray(array $data): Kinsfolk
    {
        $requiredFields = [
            'id_recipient',
            'full_name',
            'birthday',
            'profession',
            'status',
            'ringtone',
            'hotkey'
        ];

        $missingFields = array_diff($requiredFields, array_keys($data));

        if (count($missingFields) > 0) {
            $errMsg = sprintf('Отсутствуют обязательные элементы: %s', implode(',', $missingFields));
            throw new Exception\InvalidDataStructureException($errMsg);
        }
        return new Kinsfolk(
            $data['id_recipient'],
            $data['full_name'],
            $data['birthday'],
            $data['profession'],
            $data['status'],
            $data['ringtone'],
            $data['hotkey']
        );
    }


}