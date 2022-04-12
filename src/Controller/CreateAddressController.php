<?php

namespace DD\ContactList\Controller;

use DD\ContactList\Exception\RuntimeException;
use DD\ContactList\Infrastructure\Controller\ControllerInterface;
use DD\ContactList\Infrastructure\Http\ServerResponseFactory;
use DD\ContactList\Service\ArrivalNewAddressService;
use DD\ContactList\Service\ArrivalNewAddressService\NewAddressDto;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class CreateAddressController implements ControllerInterface
{
    /**
     * Сервис добавления адреса
     *
     * @var ArrivalNewAddressService
     */
    private ArrivalNewAddressService $arrivalNewAddressService;

    /**
     * Фабрика создания серверного ответа
     *
     * @var ServerResponseFactory
     */
    private ServerResponseFactory $serverResponseFactory;

    /**
     * Менеджер сущностей
     *
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;


    /**
     * @param ArrivalNewAddressService $arrivalNewAddressService - Сервис создания нового адреса
     * @param ServerResponseFactory $serverResponseFactory       - Фабрика для http ответа
     * @param EntityManagerInterface $em                    - Соединение с БД
     */
    public function __construct(
        ArrivalNewAddressService $arrivalNewAddressService,
        ServerResponseFactory $serverResponseFactory,
        EntityManagerInterface $em
    ) {
        $this->arrivalNewAddressService = $arrivalNewAddressService;
        $this->serverResponseFactory = $serverResponseFactory;
        $this->em = $em;
    }


    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        try {
            $this->em->beginTransaction();

            $requestData = json_decode($serverRequest->getBody(), 10, JSON_THROW_ON_ERROR, JSON_THROW_ON_ERROR);

            $validationResult = $this->validateAddressData($requestData);
            if (0 === count($validationResult)) {
                $responseDto = $this->runService($requestData);

                $httpCode = 201;
                $jsonData = $this->buildJsonData($responseDto);
            } else {
                $httpCode = 400;
                $jsonData = [
                    'status' => 'fail',
                    'message' => implode('. ', $validationResult)
                ];
            }
            $this->em->flush();
            $this->em->commit();
        } catch (Throwable $e) {
            $this->em->rollback();

            $httpCode = 500;
            $jsonData = [
                'status' => 'fail',
                'message' => $e->getMessage()
            ];
        }

        return $this->serverResponseFactory->createJsonResponse($httpCode, $jsonData);
    }

    private function runService($requestData): ArrivalNewAddressService\ResultRegisteringAddressDto
    {
        $requestDto = new NewAddressDto(
            $requestData['id_recipient'],
            $requestData['address'],
            $requestData['status'],
        );

        return $this->arrivalNewAddressService->addAddress($requestDto);
    }

    private function buildJsonData(ArrivalNewAddressService\ResultRegisteringAddressDto $responseDto): array
    {
        $jsonData = [
            'id_address' => $responseDto->getIdAddress(),
            'address' => $responseDto->getAddress(),
            'status' => $responseDto->getStatus()
        ];

        $jsonData['recipient'] = array_map(static function (ArrivalNewAddressService\ContactDto $contactDto) {
            return [
                'id' => $contactDto->getId(),
                'full_name' => $contactDto->getFullName()
            ];
        }, $responseDto->getContacts());

        return $jsonData;
    }

    private function validateAddressData($requestData): array
    {
        $errs = [];

        if (false === array_key_exists('address', $requestData)) {
            throw new RuntimeException('Нет данных о адресе');
        }

        if (false === is_string($requestData['address'])) {
            throw new RuntimeException('Данные о адресе должны быть строкой');
        }

        $addressLength = strlen(trim($requestData['address']));

        if ($addressLength > 250) {
            $errs[] = 'Адрес не может быть длиннее 250 символов';
        } elseif ($addressLength === 0) {
            $errs[] = 'Адрес не может быть пустым';
        } elseif (1 !== preg_match('/[^А-Яа-я]*, [1-9]+\/?([0-9]*)?/', $requestData['address'])) {
            $errs[] = 'Адрес имеет неверный формат';
        }

        if (false === array_key_exists('id_recipient', $requestData)) {
            $errs[] = 'Нет данных о контактах';
        } elseif (false === is_array($requestData['id_recipient'])) {
            $errs[] = 'Данные о контакте должны быть массивом';
        } elseif (0 === count($requestData['id_recipient'])) {
            $errs[] = 'У адреса должен быть хотя бы один контакт';
        } else {
            foreach ($requestData['id_recipient'] as $contactId) {
                if (false === is_int($contactId)) {
                    $errs[] = 'список id контактов имеет некорректные значения';
                    break;
                }
            }
        }

        return $errs;
    }
}
