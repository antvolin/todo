<?php

namespace BeeJeeMVC\Tests\Model;

use BeeJeeMVC\Lib\Exceptions\ForbiddenStatusException;
use BeeJeeMVC\Model\Status;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    /**
     * @test
     *
     * @param $status
     *
     * @dataProvider validStatuses
     *
     * @throws ForbiddenStatusException
     */
    public function shouldBeConstructable($status): void
    {
        $obj = new Status($status);

        $this->assertEquals($status, $obj);
    }

    /**
     * @test
     *
     * @param $status
     *
     * @dataProvider notValidStatuses
     *
     * @throws ForbiddenStatusException
     */
    public function shouldBeNotConstructableWithNotValidStatus($status): void
    {
        $this->expectException(ForbiddenStatusException::class);

        new Status($status);
    }

    /**
     * @return array
     */
    public function notValidStatuses(): array
    {
        return [
            ['test'],
            ['test@'],
            ['t'],
            ['@test'],
        ];
    }

    /**
     * @return array
     */
    public function validStatuses(): array
    {
        return [
            [Status::EDITED],
            [Status::DONE],
        ];
    }
}
