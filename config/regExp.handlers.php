<?php

use DD\ContactList\Controller;

return [
    '/^.*?\/(?<___CATEGORY___>[a-z]+)\/(?<___ID_RECIPIENT___>[0-9]+).*$/' => Controller\GetContactsController::class,
];
