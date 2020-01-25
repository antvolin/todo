<?php

namespace Tests\Model;

use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Model\Text;
use PHPUnit\Framework\TestCase;

class TextTest extends TestCase
{
    /**
     * @test
     *
     * @throws CannotBeEmptyException
     */
    public function shouldBeConstructable(): void
    {
        $text = new Text('test text');

        $this->assertEquals('test text', $text);
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     */
    public function shouldBeNotConstructableWithEmptyString(): void
    {
        $this->expectException(CannotBeEmptyException::class);

        new Text('');
    }
}
