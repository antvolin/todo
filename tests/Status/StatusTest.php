<?php

namespace BeeJeeMVC\Tests\Status;

use BeeJeeMVC\Model\Status;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeConstructable(): void
    {
        $status = new Status('edited');

        $this->assertEquals('edited', $status);
    }

    /**
     * @test
     *
     * @param $value
     *
     * @dataProvider notAllowedValue
     */
    public function shouldBeNotConstructableWithNotAllowedValue($value): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Status($value);
    }

    /**
     * @return array
     */
    public function notAllowedValue(): array
    {
        return [
            ['test'],
            [-1],
            [true],
            [''],
        ];
    }
}
