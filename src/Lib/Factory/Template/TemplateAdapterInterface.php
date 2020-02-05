<?php

namespace Todo\Lib\Factory\Template;

interface TemplateAdapterInterface
{
    public function render(string $name, array $context = []): string;
}
