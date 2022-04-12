<?php

namespace DD\ContactList\Entity;

use DD\ContactList\Entity\Address\Status;
use DD\ContactList\Exception\InvalidDataStructureException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Класс, описывающий сущность Адрес
 *
 * @ORM\Entity(repositoryClass=\DD\ContactList\Repository\AddressDoctrineRepository::class)
 * @ORM\Table(
 *     name="address",
 *     indexes={
 *          @ORM\Index(name="address_address_data_idx", columns={"address_data"}),
 *          @ORM\Index(name="address_status_id_idx", columns={"status_id"})
 *     }
 * )
 */
class Address
{
    /**
     * ID адреса
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="address_id_seq")
     * @ORM\Column(type="integer", name="id", nullable=false)
     *
     * @var int
     */
    private int $id;

    /**
     * Массив контактов адреса
     *
     * @ORM\ManyToMany(targetEntity=\DD\ContactList\Entity\AbstractContact::class)
     * @ORM\JoinTable(
     *     name="address_to_contact",
     *     joinColumns={@ORM\JoinColumn(name="address_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="recipient_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     *
     * @var AbstractContact[]|Collection
     */
    private Collection $recipients;

    /**
     * Адрес контакта
     *
     * @ORM\Column (name="address_data", type="string", length=255, nullable=false)
     *
     * @var string
     */
    private string $address;

    /**
     * Статус адреса (работа/дом)
     *
     * @ORM\ManyToOne(targetEntity=\DD\ContactList\Entity\Address\Status::class, cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     *
     * @var Status
     */
    private Status $status;

    /**
     * @param int $id           -  ID адреса
     * @param array $recipients -  ID контакта
     * @param string $address   - Адрес контакта
     * @param Status $status    - Статус адреса (работа/дом)
     */
    public function __construct(int $id, array $recipients, string $address, Status $status)
    {
        $this->id = $id;
        $this->recipients = new ArrayCollection($recipients);
        $this->address = $address;
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return AbstractContact[]
     */
    public function getRecipients(): array
    {
        return $this->recipients->toArray();
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getTitleContacts(): string
    {
        $titleContactId = [];
        foreach ($this->getRecipients() as $contact) {
            $titleContactId[] = $contact->getId();
        }
        return implode(', ', $titleContactId);
    }
}
