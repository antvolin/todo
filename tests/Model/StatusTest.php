<?php

namespace Tests\Model;

use PHPUnit\Framework\TestCase;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Model\Status;

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

    public function notValidStatuses(): array
    {
        return [
            ['test'],
            ['test@'],
            ['t'],
            ['@test'],
        ];
    }

    public function validStatuses(): array
    {
        $data = [];

        foreach (Status::ALLOWED_STATUSES as $status) {
            $data[] = [$status];
        }

        return $data;
    }
}
