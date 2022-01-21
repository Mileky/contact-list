<?php

namespace DD\ContactList\Repository;

use DD\ContactList\Entity\AbstractContact;
use DD\ContactList\Entity\Address;
use DD\ContactList\Entity\AddressRepositoryInterface;
use DD\ContactList\Entity\ContactRepositoryInterface;
use DD\ContactList\Infrastructure\DataLoader\DataLoaderInterface;
use JsonException;

class AddressJsonFileRepository implements AddressRepositoryInterface
{
    /**
     * Путь до данных с адресами контактов
     *
     * @var string
     */
    private string $pathToAddress;

    /**
     * Репозиторий работы с контактами
     *
     * @var ContactRepositoryInterface
     */
    private ContactRepositoryInterface $contactRepository;

    /**
     * Загрузчик данных
     *
     * @var DataLoaderInterface
     */
    private DataLoaderInterface $dataLoader;

    /**
     * Данные о контакте
     *
     * @var AbstractContact[]|null
     */
    private ?array $contactIdToInfo = null;

    /**
     * Сопоставление id контакта с номером элемента в $contactListData
     *
     * @var array|null
     */
    private ?array $idRecipient;

    /**
     * Текущий id адреса контакта
     *
     * @var int
     */
    private int $currentId;

    /**
     * Загруженные данные контактного листа
     *
     * @var array|null
     */
    private ?array $addressData = null;

    /**
     * @param DataLoaderInterface $dataLoader - Загрузчик данных
     * @param string $pathToAddress - Путь до данных с адресами контактов
     * @param ContactRepositoryInterface $contactRepository - Репозиторий работы с контактами
     */
    public function __construct(DataLoaderInterface $dataLoader,
        string $pathToAddress,
        ContactRepositoryInterface $contactRepository
    )
    {
        $this->dataLoader = $dataLoader;
        $this->pathToAddress = $pathToAddress;
        $this->contactRepository = $contactRepository;
    }

    /**
     * @inheritDoc
     */
    public function findById(int $contactId): array
    {
        $addressData = $this->loadData();
        $contactIdToInfo = $this->loadContactsData();

        $foundAddress = [];
        foreach ($addressData as $address) {
            $meetSearchCriteria = $contactId === $address['id_recipient'];

            if ($meetSearchCriteria) {
                $address['id_recipient'] = $contactIdToInfo[$address['id_recipient']];
                $foundAddress[] = Address::createFromArray($address);
            }
        }

        return $foundAddress;
    }

    /**
     * @return int
     */
    public function nextId(): int
    {
        $this->loadData();
        ++$this->currentId;

        return $this->currentId;
    }

    /**
     * @param Address $address
     *
     * @return Address
     * @throws JsonException
     */
    public function add(Address $address): Address
    {
        $this->loadData();
        $item = $this->buildJsonDataAddress($address);
        $this->addressData[] = $item;
        $data = $this->addressData;
        $this->idRecipient[$address->getIdRecipient()] = array_key_last($this->addressData);

        $jsonStr = json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->pathToAddress, $jsonStr);

        return $address;
    }

    /**
     * Загрузка данных о контактах
     *
     * @return array
     */
    private function loadContactsData(): array
    {
        if (null === $this->contactIdToInfo) {

            $contacts = $this->contactRepository->findBy();
            $contactIdToInfo = [];

            foreach ($contacts as $contact) {
                $contactIdToInfo[$contact->getIdRecipient()] = $contact;
            }
            $this->contactIdToInfo = $contactIdToInfo;
        }

        return $this->contactIdToInfo;
    }

    /**
     * Загрузка данных о контакном листе
     *
     * @return array
     */
    private function loadData(): array
    {
        if (null === $this->addressData) {
            $this->addressData = $this->dataLoader->loadData($this->pathToAddress);

            $this->idRecipient = array_combine(
                array_map(static function (array $v) {
                    return $v['id_recipient'];
                }, $this->addressData),
                array_keys($this->addressData)
            );
        }

        $this->currentId = max(
            array_map(
                static function (array $v) {
                    return $v['id_address'];
                },
                $this->addressData
            )
        );

        return $this->addressData;
    }

    /**
     * Сериализация данных адреса контакта
     *
     * @param Address $address
     *
     * @return array
     */
    private function buildJsonDataAddress(Address $address): array
    {
        return [
            'id_address' => $address->getIdAddress(),
            'id_recipient' => $address->getIdRecipient(),
            'address' => $address->getAddress(),
            'status' => $address->getStatus()
        ];
    }

}