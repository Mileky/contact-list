<?php

namespace DD\ContactList\Controller;

use DD\ContactList\Infrastructure\Controller\ControllerInterface;
use DD\ContactList\Infrastructure\Http\HttpResponse;
use DD\ContactList\Infrastructure\Http\ServerRequest;
use DD\ContactList\Infrastructure\Http\ServerResponseFactory;
use DD\ContactList\Service\ArrivalNewAddressService;
use DD\ContactList\Service\ArrivalNewAddressService\NewAddressDto;
use Throwable;

class CreateAddressController implements ControllerInterface
{

    private ArrivalNewAddressService $arrivalNewAddressService;

    /**
     * @param ArrivalNewAddressService $arrivalNewAddressService
     */
    public function __construct(ArrivalNewAddressService $arrivalNewAddressService)
    {
        $this->arrivalNewAddressService = $arrivalNewAddressService;
    }


    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequest $serverRequest): HttpResponse
    {
        try {
            $requestData = json_decode($serverRequest->getBody(), 10, JSON_THROW_ON_ERROR, JSON_THROW_ON_ERROR);

            $responseDto = $this->runService($requestData);

            $httpCode = 201;
            $jsonData = $this->buildJsonData($responseDto);

        } catch (Throwable $e) {
            $httpCode = 500;
            $jsonData = [
                'status' => 'fail',
                'message' => $e->getMessage()
            ];
        }

        return ServerResponseFactory::createJsonResponse($httpCode, $jsonData);

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
            'id_address' => $responseDto->getIdAddress(),
            'id_recipient' => $responseDto->getIdContact(),
            'address' => $responseDto->getAddress(),
            'status' => $responseDto->getStatus()
        ];
    }
}