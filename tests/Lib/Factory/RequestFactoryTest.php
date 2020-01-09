<?php

namespace BeeJeeMVC\Tests\Lib\Factory;

use BeeJeeMVC\Lib\Factory\RequestFactory;
use PHPUnit\Framework\TestCase;

class RequestFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeCreatedRequest(): void
    {
        $request = (new RequestFactory())->create();
        $this->assertTrue($request->hasSession());
    }
}
