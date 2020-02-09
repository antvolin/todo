<?php

namespace Tests\Lib\Factory\Service;

use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Factory\Service\PdoServiceFactory;
use Todo\Lib\Service\DB\PdoDBService;

class PdoServiceFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeCreatablePdoService(): void
    {
        $factory = new PdoServiceFactory(
            App::getEntityName(),
            App::getRepositoryType(),
            App::getDbFolderName()
        );
        $pdoService = $factory->createService();

        $this->assertInstanceOf(PdoDBService::class, $pdoService);
    }
}
