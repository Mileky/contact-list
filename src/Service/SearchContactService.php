<?php

namespace DD\ContactList\Service;

use DD\ContactList\Entity\AbstractContact;
use DD\ContactList\Entity\Colleague;
use DD\ContactList\Entity\ContactRepositoryInterface;
use DD\ContactList\Entity\Customer;
use DD\ContactList\Entity\Kinsfolk;
use DD\ContactList\Entity\Recipient;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;
use DD\ContactList\Service\SearchContactService\ColleagueDto;
use DD\ContactList\Service\SearchContactService\ContactDto;
use DD\ContactList\Service\SearchContactService\CustomerDto;
use DD\ContactList\Service\SearchContactService\KinsfolkDto;
use DD\ContactList\Service\SearchContactService\SearchContactServiceCriteria;
use DD\ContactList\Exception;

/**
 * Сервис поиска контактов
 */
final class SearchContactService
{
    /**
     * Логгер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Репозиторий для работы с контактами
     *
     * @var ContactRepositoryInterface
     */
    private ContactRepositoryInterface $contactRepository;


    /**
     * @param LoggerInterface            $logger
     * @param ContactRepositoryInterface $contactRepository
     */
    public function __construct(
        LoggerInterface $logger,
        ContactRepositoryInterface $contactRepository
    ) {
        $this->logger = $logger;
        $this->contactRepository = $contactRepository;
    }


    /**
     * Поиск по заданным критериям
     *
     * @param SearchContactServiceCriteria $searchCriteria - критерии поиска
     *
     * @return ContactDto[]
     */
    public function search(SearchContactServiceCriteria $searchCriteria): array
    {
        $criteria = $this->searchCriteriaToArray($searchCriteria);

        if (array_key_exists('category', $criteria) && 1 === count($criteria)) {
            $entitiesCollection = $this->contactRepository->findByCategory($criteria);
        } else {
            $entitiesCollection = $this->contactRepository->findBy($criteria);
        }


        $dtoCollection = [];
        foreach ($entitiesCollection as $entity) {
            $dtoCollection[] = $this->createDto($entity);
        }
        $this->log('found text document: ' . count($entitiesCollection));
        return $dtoCollection;
    }


    /**
     * Создание объекта Dto
     *
     * @param AbstractContact $contact
     *
     * @return ContactDto
     */
    private function createDto(AbstractContact $contact): ContactDto
    {
        $kinsfolkDto = null;
        $customerDto = null;
        $colleagueDto = null;

        if ($contact instanceof Kinsfolk) {
            $kinsfolkDto = new KinsfolkDto(
                $contact->getStatus(),
                $contact->getRingtone(),
                $contact->getHotkey()
            );
        } elseif ($contact instanceof Customer) {
            $customerDto = new CustomerDto(
                $contact->getContractNumber(),
                $contact->getAverageTransactionAmount(),
                $contact->getDiscount(),
                $contact->getTimeToCall()
            );
        } elseif ($contact instanceof Colleague) {
            $colleagueDto = new ColleagueDto(
                $contact->getDepartment(),
                $contact->getPosition(),
                $contact->getRoomNumber()
            );
        }

        return new ContactDto(
            $this->getContactType($contact),
            $contact->getIdRecipient(),
            $contact->getFullName(),
            $contact->getBirthday(),
            $contact->getProfession(),
            $kinsfolkDto,
            $customerDto,
            $colleagueDto
        );
    }

    /**
     * Логирование
     *
     * @param string $msg - логируемое сообщение
     *
     * @return void
     */
    private function log(string $msg): void
    {
        $this->logger->debug($msg);
    }

    /**
     * Возвращает категорию контакта
     *
     * @param AbstractContact $contact
     *
     * @return string
     */
    private function getContactType(AbstractContact $contact): string
    {
        if ($contact instanceof Recipient) {
            $type = ContactDto::TYPE_RECIPIENT;
        } elseif ($contact instanceof Customer) {
            $type = ContactDto::TYPE_CUSTOMER;
        } elseif ($contact instanceof Kinsfolk) {
            $type = ContactDto::TYPE_KINSFOLK;
        } elseif ($contact instanceof Colleague) {
            $type = ContactDto::TYPE_COLLEAGUE;
        } else {
            throw new Exception\RuntimeException('Неизвестная категория контакта');
        }
        return $type;
    }

    /**
     * Преобразование критериев поиска в массив
     *
     * @param SearchContactServiceCriteria $searchCriteria
     *
     * @return void
     */
    private function searchCriteriaToArray(SearchContactServiceCriteria $searchCriteria): array
    {
        $criteriaForRepository = [
            'category'                   => $searchCriteria->getCategory(),
            'id_recipient'               => $searchCriteria->getId(),
            'full_name'                  => $searchCriteria->getFullName(),
            'birthday'                   => $searchCriteria->getBirthday(),
            'profession'                 => $searchCriteria->getProfession(),
            'status'                     => $searchCriteria->getStatus(),
            'ringtone'                   => $searchCriteria->getRingtone(),
            'hotkey'                     => $searchCriteria->getHotkey(),
            'contract_number'            => $searchCriteria->getContractNumber(),
            'average_transaction_amount' => $searchCriteria->getAverageTransactionAmount(),
            'discount'                   => $searchCriteria->getDiscount(),
            'time_to_call'               => $searchCriteria->getTimeToCall(),
            'department'                 => $searchCriteria->getDepartment(),
            'position'                   => $searchCriteria->getPosition(),
            'room_number'                => $searchCriteria->getRoomNumber(),

        ];

        return array_filter($criteriaForRepository, static function ($v): bool {
            return null !== $v;
        });
    }
}
