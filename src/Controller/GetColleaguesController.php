<?php

namespace DD\ContactList\Controller;

class GetColleaguesController extends GetCustomersCollectionController
{
    /**
     * @inheritDoc
     */
    protected function buildHttpCode(array $foundColleagues): int
    {
        return 0 === count($foundColleagues) ? 404 : 200;
    }

    /**
     * @inheritDoc
     */
    protected function buildResult(array $foundColleagues)
    {
        return 1 === count($foundColleagues) ? current($foundColleagues) : [
            'status' => 'fail',
            'message' => 'entity not found'
        ];

    }
}