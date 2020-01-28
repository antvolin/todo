<?php

namespace Tests\Lib\Factory\Request;

use PHPUnit\Framework\TestCase;
use Todo\Lib\Factory\Request\RequestFactory;

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
