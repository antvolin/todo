<?php

namespace Tests\Lib\Factory\Service;

use Todo\Lib\App;
use Todo\Lib\Factory\Service\PdoServiceFactory;
use PHPUnit\Framework\TestCase;

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
