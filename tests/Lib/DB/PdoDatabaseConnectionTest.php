<?php

namespace Tests\Lib\DB;

use PHPUnit\Framework\TestCase;
use Todo\Lib\DB\PdoDatabaseConfiguration;
use Todo\Lib\DB\PdoDatabaseConnection;

class PdoDatabaseConnectionTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeGettingDsn(): void
    {
        $config = $this->createMock(PdoDatabaseConfiguration::class);
        $config->method('getEntityName')->willReturn('name');
        $config->method('getDbType')->willReturn('db_type');
        $config->method('getDbFolderName')->willReturn('db_folder_name/');

        $connection = new PdoDatabaseConnection($config);

        $this->assertEquals('db_type:/home/antvolin/projects/todo/src/Lib/../../db_folder_name/name', $connection->getDsn());
    }
}
