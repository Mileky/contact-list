<?php

namespace DD\ContactList\Infrastructure\ViewTemplate;

interface ViewTemplateInterface
{
    /**
     * Рендер данных
     *
     * @param string $template - путь до шаблона
     * @param array $context - данные для рендеринга
     *
     * @return string
     */
    public function render(string $template, array $context): string;
}