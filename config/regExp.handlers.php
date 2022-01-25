<?php

use DD\ContactList\Controller;

return [
    '/^.*?\/(?<___CATEGORY___>recipients)\/(?<___ID_RECIPIENT___>[0-9]+).*$/' => Controller\GetContactsController::class,
    '/^.*?\/(?<___CATEGORY___>customers)\/(?<___ID_RECIPIENT___>[0-9]+).*$/' => Controller\GetContactsController::class,
    '/^.*?\/(?<___CATEGORY___>kinsfolk)\/(?<___ID_RECIPIENT___>[0-9]+).*$/' => Controller\GetContactsController::class,
    '/^.*?\/(?<___CATEGORY___>colleagues)\/(?<___ID_RECIPIENT___>[0-9]+).*$/' => Controller\GetContactsController::class,
];
