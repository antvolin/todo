<?php

namespace Tests\Lib\Service\Pdo;

use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Exceptions\PdoConnectionException;
use Todo\Lib\Service\Pdo\PdoService;

class PdoServiceTest extends TestCase
{
    /**
     * PdoService $pdoService
     */
    private $pdoService;

    protected function setUp()
    {
        $this->pdoService = new PdoService(App::getEntityName(), App::getStorageType(), App::getDbFolderName());
    }

    /**
     * @test
     */
    public function shouldBeGettingPdo(): void
    {
        $this->assertTrue(method_exists($this->pdoService, 'getPdo'));
        $this->assertTrue(method_exists($this->pdoService, 'createTables'));
    }

    /**
     * @test
     *
     * @throws PdoConnectionException
     */
    public function shouldBeCreatedTables(): void
    {
        $this->markTestSkipped();

        $this->pdoService->getPdo();
        $this->assertTrue($this->pdoService->createTables());
    }
}
