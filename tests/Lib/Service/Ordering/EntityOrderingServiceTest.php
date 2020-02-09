<?php

namespace Tests\Lib\Service\Ordering;

use PHPUnit\Framework\TestCase;
use Todo\Lib\Service\Ordering\EntityOrderingService;

class EntityOrderingServiceTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeOrderingCollection(): void
    {
        $this->markTestIncomplete();
    }

    /**
     * @test
     */
    public function shouldBeGettingValidNextOrder(): void
    {
        $service = new EntityOrderingService();

        $this->assertEquals(EntityOrderingService::DESC, $service->getNextOrder(null));
        $this->assertEquals(EntityOrderingService::DESC, $service->getNextOrder(EntityOrderingService::ASC));
        $this->assertEquals(EntityOrderingService::ASC, $service->getNextOrder(EntityOrderingService::DESC));
        $this->assertEquals(EntityOrderingService::ASC, $service->getNextOrder('test'));
    }
}
