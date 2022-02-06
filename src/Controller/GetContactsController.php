<?php

namespace DD\ContactList\Controller;

final class GetContactsController extends GetContactsCollectionController
{
    /**
     * @inheritDoc
     */
    protected function buildHttpCode(array $foundContacts): int
    {
        return 0 === count($foundContacts) ? 404 : 200;
    }

    /**
     * @inheritDoc
     */
    protected function buildResult(array $foundContacts): array
    {
        return count($foundContacts) ? $this->serializeContact(current($foundContacts)) : [
            'status'  => 'fail',
            'message' => 'entity not found'
        ];
    }
}
