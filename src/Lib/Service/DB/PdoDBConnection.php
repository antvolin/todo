<?php

namespace Todo\Lib\Service\DB;

class PdoDBConnection
{
    private PdoDBConfiguration $configuration;

    public function __construct(PdoDBConfiguration $config)
    {
        $this->configuration = $config;
    }

    public function getDsn(): string
    {
        return sprintf(
            '%s:%s%s%s%s',
            $this->configuration->getDbType(),
            dirname(__DIR__),
            '/../../../',
            $this->configuration->getDbFolderName(),
            $this->configuration->getEntityName()
        );
    }
}
