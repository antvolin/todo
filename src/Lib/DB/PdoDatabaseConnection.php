<?php

namespace Todo\Lib\DB;

class PdoDatabaseConnection implements DatabaseConnectionInterface
{
    private DatabaseConfigurationInterface $configuration;

    public function __construct(DatabaseConfigurationInterface $config)
    {
        $this->configuration = $config;
    }

    public function getDsn(): string
    {
        return sprintf(
            '%s:%s%s%s%s',
            $this->configuration->getDbType(),
            dirname(__DIR__),
            '/../../',
            $this->configuration->getDbFolderName(),
            $this->configuration->getEntityName()
        );
    }
}
