<?php

namespace DD\ContactList\Controller;

class GetKinsfolkController extends GetKinsfolkCollectionController
{
    /**
     * @inheritDoc
     */
    protected function buildHttpCode(array $foundKinsfolk): int
    {
        return 0 === count($foundKinsfolk) ? 404 : 200;
    }

    /**
     * @inheritDoc
     */
    protected function buildResult(array $foundKinsfolk)
    {
        return 1 === count($foundKinsfolk) ? current($foundKinsfolk) : [
            'status' => 'fail',
            'message' => 'entity not found'
        ];

    }
}