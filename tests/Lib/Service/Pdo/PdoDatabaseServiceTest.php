<?php

namespace Tests\Lib\Service\Pdo;

use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Factory\Service\PdoServiceFactory;
use Todo\Lib\Service\Pdo\PdoDatabaseService;

class PdoDatabaseServiceTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeCreatedTables(): void
    {
        if ('pdo' !== App::getRepositoryType()) {
            $this->markTestSkipped();
        }

        $pdo = (new PdoServiceFactory(
            App::getEntityName(),
            App::getDbType(),
            App::getDbFolderName()
        ))->create()->getPdo();
        $pdoDatabaseService = new PdoDatabaseService($pdo, App::getEntityName());

        $this->assertTrue($pdoDatabaseService->createTables());
    }
}
