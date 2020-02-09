<?php

namespace Todo\Lib\Service\DB;

class RedisDBConnection
{
    private RedisDBConfiguration $configuration;

    public function __construct(RedisDBConfiguration $config)
    {
        $this->configuration = $config;
    }

    public function getDsn(): string
    {
        return sprintf(
            'redis://%s@%s:%s',
            $this->configuration->getPassword(),
            $this->configuration->getHost(),
            $this->configuration->getPort()
        );
    }
}
