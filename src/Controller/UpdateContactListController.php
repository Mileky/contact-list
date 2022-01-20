<?php

namespace DD\ContactList\Controller;

use DD\ContactList\Infrastructure\Controller\ControllerInterface;
use DD\ContactList\Infrastructure\Http\HttpResponse;
use DD\ContactList\Infrastructure\Http\ServerRequest;

/**
 * Контроллер переноса контакта в черный список (изменение статуса 'blacklist')
 */
class UpdateContactListController implements ControllerInterface
{




    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequest $serverServerRequest): HttpResponse
    {

    }
}