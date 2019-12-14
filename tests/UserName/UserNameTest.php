<?php

namespace BeeJeeMVC\Tests\UserName;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use BeeJeeMVC\UserName;

class UserNameTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeConstructable(): void
    {
        $userName = new UserName('test user name');

        $this->assertEquals('test user name', $userName->getValue());
    }

    /**
     * @test
     */
    public function shouldBeNotConstructableWithEmptyString(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new UserName('');
    }
}
