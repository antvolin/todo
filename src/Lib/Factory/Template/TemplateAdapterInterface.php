<?php

namespace Todo\Lib\Factory\Template;

interface TemplateAdapterInterface
{
    /**
     * @param string $name
     * @param array $context
     *
     * @return string
     */
    public function render(string $name, array $context = []): string;
}
