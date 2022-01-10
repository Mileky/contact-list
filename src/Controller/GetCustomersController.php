<?php

namespace DD\ContactList\Controller;

class GetCustomersController extends GetCustomersCollectionController
{
    /**
     * @inheritDoc
     */
    protected function buildHttpCode(array $foundCustomers): int
    {
        return 0 === count($foundCustomers) ? 404 : 200;
    }

    /**
     * @inheritDoc
     */
    protected function buildResult(array $foundCustomers)
    {
        return 1 === count($foundCustomers) ? current($foundCustomers) : [
            'status' => 'fail',
            'message' => 'entity not found'
        ];

    }
}