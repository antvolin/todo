<?php

namespace Tests\Lib\Factory\Service;

use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Factory\Service\PdoServiceFactory;

class PdoServiceFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeCreatablePdoService(): void
    {
        $factory = new PdoServiceFactory(
            App::getEntityName(),
            App::getStorageType(),
            App::getDbFolderName()
        );
        $pdoService = $factory->create();

        $this->assertTrue(method_exists($pdoService, 'getPdo'));
        $this->assertTrue(method_exists($pdoService, 'createTables'));
    }
}
