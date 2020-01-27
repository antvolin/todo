<?php

namespace Todo\Lib\Factory\Template;

interface TemplateFactoryInterface
{
    /**
     * @return TemplateAdapterInterface
     */
    public function create(): TemplateAdapterInterface;
}
