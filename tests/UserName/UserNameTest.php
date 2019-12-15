<?php

namespace BeeJeeMVC\Tests\UserName;

use BeeJeeMVC\Model\UserName;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class UserNameTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeConstructable(): void
    {
        $userName = new UserName('test user name');

        $this->assertEquals('test user name', $userName);
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
