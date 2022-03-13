<?php

namespace DD\ContactList\Controller;

use DD\ContactList\Infrastructure\Controller\ControllerInterface;
use DD\ContactList\Infrastructure\Http\ServerResponseFactory;
use DD\ContactList\Service\ArrivalNewAddressService;
use DD\ContactList\Service\ArrivalNewAddressService\NewAddressDto;
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
     * @param ArrivalNewAddressService $arrivalNewAddressService
     * @param ServerResponseFactory $serverResponseFactory
     */
    public function __construct(
        ArrivalNewAddressService $arrivalNewAddressService,
        ServerResponseFactory $serverResponseFactory
    ) {
        $this->arrivalNewAddressService = $arrivalNewAddressService;
        $this->serverResponseFactory = $serverResponseFactory;
    }


    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        try {
            $requestData = json_decode($serverRequest->getBody(), 10, JSON_THROW_ON_ERROR, JSON_THROW_ON_ERROR);

            $responseDto = $this->runService($requestData);

            $httpCode = 201;
            $jsonData = $this->buildJsonData($responseDto);
        } catch (Throwable $e) {
            $httpCode = 500;
            $jsonData = [
                'status'  => 'fail',
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
        return [
            'id_address'   => $responseDto->getIdAddress(),
            'id_recipient' => $responseDto->getContacts(),
            'address'      => $responseDto->getAddress(),
            'status'       => $responseDto->getStatus()
        ];
    }
}
