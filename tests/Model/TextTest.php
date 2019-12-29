<?php

namespace BeeJeeMVC\Tests\Text;

use BeeJeeMVC\Model\Text;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TextTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeConstructable(): void
    {
        $text = new Text('test text');

        $this->assertEquals('test text', $text);
    }

    /**
     * @test
     */
    public function shouldBeNotConstructableWithEmptyString(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Text('');
    }
}