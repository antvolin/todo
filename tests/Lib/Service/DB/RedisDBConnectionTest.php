<?php

namespace Tests\Lib\Service\DB;

use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Service\DB\RedisDBConfiguration;
use Todo\Lib\Service\DB\RedisDBConnection;

class RedisDBConnectionTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeGettingDsn(): void
    {
        $config = $this->createMock(RedisDBConfiguration::class);
        $config->method('getHost')->willReturn('localhost');
        $config->method('getPort')->willReturn(6379);
        $config->method('getPassword')->willReturn(App::getRedisPassword());

        $connection = new RedisDBConnection($config);

        $this->assertEquals('redis://'.App::getRedisPassword().'@localhost:6379', $connection->getDsn());
    }
}
