<?php

namespace Todo\Lib\Factory\Service;

use Todo\Lib\Service\DB\RedisDBConfiguration;
use Todo\Lib\Service\DB\RedisDBConnection;
use Todo\Lib\Service\DB\RedisDBService;

class RedisDBServiceFactory implements ServiceFactoryInterface
{
    private string $host;
    private int $port;
    private string $password;

    public function __construct(
        string $host,
        int $port,
        string $password
    )
    {
        $this->host = strtolower($host);
        $this->port = $port;
        $this->password = $password;
    }

    public function createService(): RedisDBService
    {
        $pdoDatabaseConfiguration = new RedisDBConfiguration(
            $this->host,
            $this->port,
            $this->password
        );
        $redisDatabaseConnection = new RedisDBConnection(
            $pdoDatabaseConfiguration
        );

        return new RedisDBService($redisDatabaseConnection);
    }
}
