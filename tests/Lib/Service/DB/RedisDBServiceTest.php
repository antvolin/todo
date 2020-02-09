<?php

namespace Tests\Lib\Service\DB;

use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Exceptions\RedisConnectionException;
use Todo\Lib\Service\DB\RedisDBConfiguration;
use Todo\Lib\Service\DB\RedisDBConnection;
use Todo\Lib\Service\DB\RedisDBService;

class RedisDBServiceTest extends TestCase
{
    private \Redis $redis;

    /**
     * @throws RedisConnectionException
     */
    protected function setUp()
    {
        $config = new RedisDBConfiguration(
            App::getRedisHost(),
            App::getRedisPort(),
            App::getRedisPassword()
        );
        $connection = new RedisDBConnection($config);
        $service = new RedisDBService($connection);
        $this->redis = $service->getDBInstance();
    }

    /**
     * @test
     */
    public function shouldBeGettingRedis(): void
    {
        $this->assertInstanceOf(\Redis::class, $this->redis);
    }
}
