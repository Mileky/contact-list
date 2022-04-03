<?php

namespace DD\ContactList\Entity;

use DD\ContactList\Exception;
use Doctrine\ORM\Mapping as ORM;

/**
 * Класс описывающий Список контактов
 *
 * @ORM\Entity()
 * @ORM\Table(name="contact_list")
 */
class ContactList
{
    /**
     * ID записи
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="contact_list_id_seq")
     *
     * @var int
     */
    private int $id;

    /**
     * ID контакта
     *
     * @ORM\OneToOne(targetEntity=\DD\ContactList\Entity\AbstractContact::class)
     * @ORM\JoinColumn(name="id_recipient", referencedColumnName="id")
     *
     * @var AbstractContact
     */
    private AbstractContact $recipient;

    /**
     * Наличие в черном списке
     *
     * @ORM\Column(name="blacklist", type="boolean", nullable=false)
     *
     * @var bool
     */
    private bool $blacklist;

    /**
     * @param int $id                    - ID записи
     * @param AbstractContact $recipient - ID контакта
     * @param bool $blacklist            - Наличие в черном списке
     */
    public function __construct(int $id, AbstractContact $recipient, bool $blacklist)
    {
        $this->id = $id;
        $this->recipient = $recipient;
        $this->blacklist = $blacklist;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return AbstractContact
     */
    public function getRecipient(): AbstractContact
    {
        return $this->recipient;
    }

    /**
     * @return bool
     */
    public function isBlacklist(): bool
    {
        return $this->blacklist;
    }

    public function moveToBlacklist(): self
    {
        if (true === $this->blacklist) {
            throw new Exception\RuntimeException(
                "Контакт с id {$this->getRecipient()->getIdRecipient()} уже находится в ЧС"
            );
        }

        $this->blacklist = true;

        return $this;
    }

    /**
     * Создание списка контактов
     *
     * @param array $data
     *
     * @return ContactList
     */
    public static function createFromArray(array $data): ContactList
    {
        $requiredFields = [
            'id',
            'id_recipient',
            'blacklist'
        ];

        $missingFields = array_diff($requiredFields, array_keys($data));

        if (count($missingFields) > 0) {
            $errMsg = sprintf('Отсутствуют обязательные элементы: %s', implode(',', $missingFields));
            throw new Exception\InvalidDataStructureException($errMsg);
        }

        return new ContactList(
            $data['id'],
            $data['id_recipient'],
            $data['blacklist']
        );
    }
}
