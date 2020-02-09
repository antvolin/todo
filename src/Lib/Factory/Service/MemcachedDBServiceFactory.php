<?php

namespace Todo\Lib\Factory\Service;

use Todo\Lib\Service\DB\MemcachedDBConfiguration;
use Todo\Lib\Service\DB\MemcachedDBConnection;
use Todo\Lib\Service\DB\MemcachedDBService;

class MemcachedDBServiceFactory implements ServiceFactoryInterface
{
    private string $host;

    public function __construct(
        string $host
    )
    {
        $this->host = strtolower($host);
    }

    public function createService(): MemcachedDBService
    {
        $config = new MemcachedDBConfiguration(
            $this->host
        );
        $connection = new MemcachedDBConnection(
            $config
        );

        return new MemcachedDBService($connection);
    }
}
