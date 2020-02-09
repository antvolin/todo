<?php

namespace Todo\Lib\Service\DB;

use InvalidArgumentException;
use Redis;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Todo\Lib\Exceptions\RedisConnectionException;

class RedisDBService implements DBServiceInterface
{
    private RedisDBConnection $connection;

    public function __construct(RedisDBConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return Redis
     *
     * @throws RedisConnectionException
     */
    public function getDBInstance(): Redis
    {
        $dsn = $this->connection->getDsn();

        try {
            $redis = RedisAdapter::createConnection($dsn);
        } catch (InvalidArgumentException $exception) {
            throw new RedisConnectionException(sprintf('%s with dsn %s', $exception->getMessage(), $dsn));
        }

        return $redis;
    }
}
