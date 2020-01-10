<?php

namespace BeeJeeMVC\Tests\Lib;

use BeeJeeMVC\Lib\PathSeparator;
use PHPUnit\Framework\TestCase;

class PathSeparatorTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeSeparateStringPathToArray(): void
    {
        $expected = [
            'part1',
            'part2',
            'part3',
            'part4',
        ];
        $actual = PathSeparator::separate('/part1/part2/part3/part4');

        $this->assertSame($expected, $actual);
    }
}
