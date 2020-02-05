<?php

namespace Todo\Lib\Factory\Template;

interface TemplateFactoryInterface
{
    public function create(): TemplateAdapterInterface;
}
