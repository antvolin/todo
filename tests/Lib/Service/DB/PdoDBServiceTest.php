<?php

namespace Tests\Lib\Service\DB;

use PDO;
use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Exceptions\PdoConnectionException;
use Todo\Lib\Service\DB\PdoDBConfiguration;
use Todo\Lib\Service\DB\PdoDBConnection;
use Todo\Lib\Service\DB\PdoDBService;

class PdoDBServiceTest extends TestCase
{
    private PdoDBService $pdoService;
    private PDO $pdo;

    /**
     * @throws PdoConnectionException
     */
    protected function setUp()
    {
        $pdoDatabaseConfiguration = new PdoDBConfiguration(App::getEntityName(), App::getDbType(), App::getDbFolderName());
        $pdoDatabaseConnection = new PdoDBConnection($pdoDatabaseConfiguration);
        $this->pdoService = new PdoDBService($pdoDatabaseConnection, App::getEntityName());
        $this->pdo = $this->pdoService->getDBInstance();
    }

    /**
     * @test
     */
    public function shouldBeGettingPdo(): void
    {
        $this->assertInstanceOf(PDO::class, $this->pdo);
    }

    /**
     * @test
     */
    public function shouldBeCreatedTables(): void
    {
        if ('pdo' !== App::getRepositoryType()) {
            $this->markTestSkipped();
        }

        $this->assertTrue($this->pdoService->createTables());
    }
}
