<?php

use DD\ContactList\Controller;
use DD\ContactList\Infrastructure\AppConfig;
use DD\ContactList\Infrastructure\Http\ServerRequest;
use DD\ContactList\Infrastructure\Logger\LoggerInterface;

//return [
//    '/recipients' => static function (ServerRequest $serverRequest, LoggerInterface $logger, AppConfig $appConfig) {
//        return (new Controller\FindRecipient($logger, $appConfig))($serverRequest);
//    },
//    '/customers' => static function (ServerRequest $serverRequest, LoggerInterface $logger, AppConfig $appConfig) {
//        return (new Controller\FindCustomers($logger, $appConfig))($serverRequest);
//    },
//    '/colleagues' => static function (ServerRequest $serverRequest, LoggerInterface $logger, AppConfig $appConfig) {
//        return (new Controller\FindColleagues($logger, $appConfig))($serverRequest);
//    },
//    '/kinsfolk' => static function (ServerRequest $serverRequest, LoggerInterface $logger, AppConfig $appConfig) {
//        return (new Controller\FindKinsfolk($logger, $appConfig))($serverRequest);
//    },
//];

return [
    '/recipients' => Controller\FindRecipient::class,
    '/customers' => Controller\FindCustomers::class,
    '/colleagues' => Controller\FindColleagues::class,
    '/kinsfolk' => Controller\FindKinsfolk::class,
];
