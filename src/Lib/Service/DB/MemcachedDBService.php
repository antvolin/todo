<?php

namespace Todo\Lib\Service\DB;

use ErrorException;
use Memcached;
use Symfony\Component\Cache\Adapter\MemcachedAdapter;
use Todo\Lib\Exceptions\MemcachedConnectionException;

class MemcachedDBService implements DBServiceInterface
{
    private MemcachedDBConnection $connection;

    public function __construct(MemcachedDBConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return Memcached
     *
     * @throws MemcachedConnectionException
     */
    public function getDBInstance(): Memcached
    {
        $dsn = $this->connection->getDsn();

        try {
            $memcached = MemcachedAdapter::createConnection($dsn);
        } catch (ErrorException $exception) {
            throw new MemcachedConnectionException(sprintf('%s with dsn %s', $exception->getMessage(), $dsn));
        }

        return $memcached;
    }
}
