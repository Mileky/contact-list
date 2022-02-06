<?php

namespace DD\ContactList\Controller;

use DD\ContactList\Exception;
use DD\ContactList\Infrastructure\Controller\ControllerInterface;
use DD\ContactList\Infrastructure\Http\HttpResponse;
use DD\ContactList\Infrastructure\Http\ServerRequest;
use DD\ContactList\Infrastructure\Http\ServerResponseFactory;
use DD\ContactList\Service\AddBlacklistContactService;
use Throwable;

/**
 * Контроллер переноса контакта в черный список (изменение статуса 'blacklist')
 */
class UpdateContactListController implements ControllerInterface
{
    /**
     * Сервис занесения контакта в ЧС
     *
     * @var AddBlacklistContactService
     */
    private AddBlacklistContactService $addBlacklistContactService;

    /**
     * @param AddBlacklistContactService $addBlacklistContactService - Сервис занесения контакта в ЧС
     */
    public function __construct(AddBlacklistContactService $addBlacklistContactService)
    {
        $this->addBlacklistContactService = $addBlacklistContactService;
    }


    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequest $serverRequest): HttpResponse
    {
        try {
            $attributes = $serverRequest->getAttributes();
            if (false === array_key_exists('id_recipient', $attributes)) {
                throw new Exception\RuntimeException('Нет id контакта для занесения в ЧС');
            }

            $resultDto = $this->addBlacklistContactService->addBlacklist($attributes['id_recipient']);

            $httpCode = 200;
            $jsonData = $this->buildJsonData($resultDto);
        } catch (AddBlacklistContactService\Exception\ContactNotFoundException $e) {
            $httpCode = 404;
            $jsonData = [
                'status'  => 'fail',
                'message' => $e->getMessage()
            ];
        } catch (Throwable $e) {
            $httpCode = 500;
            $jsonData = [
                'status'  => 'fail',
                'message' => $e->getMessage()
            ];
        }


        return ServerResponseFactory::createJsonResponse($httpCode, $jsonData);
    }

    private function buildJsonData(AddBlacklistContactService\ResultAddBlacklistDto $resultDto): array
    {
        return [
            'blacklist' => $resultDto->isStatus()
        ];
    }
}
