<?php

namespace Tests\Lib\Service\Pdo;

use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\DB\PdoDatabaseConfiguration;
use Todo\Lib\DB\PdoDatabaseConnection;
use Todo\Lib\Service\Pdo\PdoService;

class PdoServiceTest extends TestCase
{
    private PdoService $pdoService;

    protected function setUp()
    {
        $pdoDatabaseConfiguration = new PdoDatabaseConfiguration(App::getEntityName(), App::getRepositoryType(), App::getDbFolderName());
        $pdoDatabaseConnection = new PdoDatabaseConnection($pdoDatabaseConfiguration);
        $this->pdoService = new PdoService($pdoDatabaseConnection);
    }

    /**
     * @test
     */
    public function shouldBeGettingPdo(): void
    {
        $this->assertTrue(method_exists($this->pdoService, 'getPdo'));
    }
}
