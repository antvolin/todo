<?php

namespace Todo\Tests\Model;

use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Model\UserName;
use PHPUnit\Framework\TestCase;

class UserNameTest extends TestCase
{
    /**
     * @test
     *
     * @throws CannotBeEmptyException
     */
    public function shouldBeConstructable(): void
    {
        $userName = new UserName('test user name');

        $this->assertEquals('test user name', $userName);
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     */
    public function shouldBeNotConstructableWithEmptyString(): void
    {
        $this->expectException(CannotBeEmptyException::class);

        new UserName('');
    }
}
