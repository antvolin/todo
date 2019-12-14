<?php

namespace BeeJeeMVC\Tests\Status;

use BeeJeeMVC\Status;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeConstructable(): void
    {
        $status = new Status('test status');

        $this->assertEquals('test status', $status);
    }

    /**
     * @test
     */
    public function shouldBeNotConstructableWithEmptyString(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Status('');
    }
}
