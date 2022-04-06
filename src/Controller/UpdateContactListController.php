<?php

namespace DD\ContactList\Controller;

use DD\ContactList\Exception;
use DD\ContactList\Infrastructure\Controller\ControllerInterface;
use DD\ContactList\Infrastructure\Http\ServerResponseFactory;
use DD\ContactList\Service\AddBlacklistContactService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
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
     * @param AddBlacklistContactService $addBlacklistContactService - Сервис занесения контакта в ЧС
     * @param ServerResponseFactory $serverResponseFactory
     * @param EntityManagerInterface $em
     */
    public function __construct(
        AddBlacklistContactService $addBlacklistContactService,
        ServerResponseFactory $serverResponseFactory,
        EntityManagerInterface $em
    ) {
        $this->addBlacklistContactService = $addBlacklistContactService;
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

            $attributes = $serverRequest->getAttributes();
            if (false === array_key_exists('id_recipient', $attributes)) {
                throw new Exception\RuntimeException('Нет id контакта для занесения в ЧС');
            }

            $resultDto = $this->addBlacklistContactService->addBlacklist($attributes['id_recipient']);

            $httpCode = 200;
            $jsonData = $this->buildJsonData($resultDto);

            $this->em->flush();
            $this->em->commit();
        } catch (AddBlacklistContactService\Exception\ContactNotFoundException $e) {
            $this->em->rollback();

            $httpCode = 404;
            $jsonData = [
                'status' => 'fail',
                'message' => $e->getMessage()
            ];
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

    private function buildJsonData(AddBlacklistContactService\ResultAddBlacklistDto $resultDto): array
    {
        return [
            'blacklist' => $resultDto->isStatus()
        ];
    }
}
