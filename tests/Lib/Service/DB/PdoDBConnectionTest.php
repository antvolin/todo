<?php

namespace Tests\Lib\Service\DB;

use PHPUnit\Framework\TestCase;
use Todo\Lib\Service\DB\PdoDBConfiguration;
use Todo\Lib\Service\DB\PdoDBConnection;

class PdoDBConnectionTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeGettingDsn(): void
    {
        $config = $this->createMock(PdoDBConfiguration::class);
        $config->method('getEntityName')->willReturn('name');
        $config->method('getDbType')->willReturn('db_type');
        $config->method('getDbFolderName')->willReturn('db_folder_name/');

        $connection = new PdoDBConnection($config);

        $this->assertEquals('db_type:/home/antvolin/projects/todo/src/Lib/Service/../../../db_folder_name/name', $connection->getDsn());
    }
}
