<?php

namespace Todo\Lib\Service\DB;

class MemcachedDBConnection
{
    private MemcachedDBConfiguration $configuration;

    public function __construct(MemcachedDBConfiguration $config)
    {
        $this->configuration = $config;
    }

    public function getDsn(): string
    {
        return sprintf(
            'memcached://%s',
            $this->configuration->getHost(),
        );
    }
}
