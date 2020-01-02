<?php

namespace BeeJeeMVC\Tests\Lib;

use BeeJeeMVC\Lib\Ordering;
use PHPUnit\Framework\TestCase;

class OrderingTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider validOrderByFields
     *
     * @param string $field
     */
    public function shouldBeGettingValidOrderBy(string $field): void
    {
        $this->assertEquals('user_name', Ordering::getOrderBy(null));
        $this->assertEquals('user_name', Ordering::getOrderBy(''));
        $this->assertEquals('user_name', Ordering::getOrderBy('test'));
        $this->assertEquals($field, Ordering::getOrderBy($field));
    }

    /**
     * @test
     *
     * @dataProvider validOrderFields
     *
     * @param string $field
     */
    public function shouldBeGettingValidOrder(string $field): void
    {
        $this->assertEquals(Ordering::ASC, Ordering::getOrder(null));
        $this->assertEquals(Ordering::ASC, Ordering::getOrder(''));
        $this->assertEquals(Ordering::ASC, Ordering::getOrder('test'));
        $this->assertEquals($field, Ordering::getOrder($field));
    }

    /**
     * @test
     */
    public function shouldBeGettingValidNextOrder(): void
    {
        $this->assertEquals(Ordering::DESC, Ordering::getNextOrder(null));
        $this->assertEquals(Ordering::DESC, Ordering::getNextOrder(Ordering::ASC));
        $this->assertEquals(Ordering::ASC, Ordering::getNextOrder(Ordering::DESC));
        $this->assertEquals(Ordering::ASC, Ordering::getNextOrder('test'));
    }

    /**
     * @return array
     */
    public function validOrderByFields(): array
    {
        $data = [];

        foreach (Ordering::ALLOWED_ORDER_BY_FIELDS as $field) {
            $data[] = [$field];
        }

        return $data;
    }

    /**
     * @return array
     */
    public function validOrderFields(): array
    {
        $data = [];

        foreach (Ordering::ALLOWED_ORDER_FIELDS as $field) {
            $data[] = [$field];
        }

        return $data;
    }
}
