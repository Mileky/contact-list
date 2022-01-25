<?php

namespace DD\ContactList\Infrastructure\ViewTemplate;

use DD\ContactList\Infrastructure\Exception\RuntimeException;

class PhtmlTemplate implements ViewTemplateInterface
{

    /**
     * @inheritDoc
     */
    public function render(string $template, array $context): string
    {
        if (false === file_exists($template)) {
            throw new RuntimeException("Некорректный путь до шаблона: '$template'");
        }

        extract($context, EXTR_SKIP);
        unset($viewData);

        ob_start();

        require $template;

        return ob_get_clean();
    }
}