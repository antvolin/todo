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
        $app = new App();
        $factory = new PdoServiceFactory(
            $app->getEntityName(),
            $app->getStorageType(),
            $app->getDbFolderName()
        );
        $service = $factory->create();

        $this->assertTrue(method_exists($service, 'getPdo'));
        $this->assertTrue(method_exists($service, 'createTables'));
    }
}
