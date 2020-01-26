<?php

namespace Tests\Lib\Factory;

use Todo\Lib\Factory\RequestFactory;
use PHPUnit\Framework\TestCase;

class RequestFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeCreatedRequest(): void
    {
        $factory = new RequestFactory();
        $request = $factory->create();

        $this->assertTrue($request->hasSession());
    }
}
