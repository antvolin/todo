<?php

namespace Tests\Lib\Service\Ordering;

use PHPUnit\Framework\TestCase;
use Todo\Lib\Service\Ordering\OrderingService;

class OrderingServiceTest extends TestCase
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
        $this->assertEquals('user_name', OrderingService::getOrderBy(null));
        $this->assertEquals('user_name', OrderingService::getOrderBy(''));
        $this->assertEquals('user_name', OrderingService::getOrderBy('test'));
        $this->assertEquals($field, OrderingService::getOrderBy($field));
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
        $this->assertEquals(OrderingService::ASC, OrderingService::getOrder(null));
        $this->assertEquals(OrderingService::ASC, OrderingService::getOrder(''));
        $this->assertEquals(OrderingService::ASC, OrderingService::getOrder('test'));
        $this->assertEquals($field, OrderingService::getOrder($field));
    }

    /**
     * @test
     */
    public function shouldBeGettingValidNextOrder(): void
    {
        $this->assertEquals(OrderingService::DESC, OrderingService::getNextOrder(null));
        $this->assertEquals(OrderingService::DESC, OrderingService::getNextOrder(OrderingService::ASC));
        $this->assertEquals(OrderingService::ASC, OrderingService::getNextOrder(OrderingService::DESC));
        $this->assertEquals(OrderingService::ASC, OrderingService::getNextOrder('test'));
    }

    public function validOrderByFields(): array
    {
        $data = [];

        foreach (OrderingService::ALLOWED_ORDER_BY_FIELDS as $field) {
            $data[] = [$field];
        }

        return $data;
    }

    public function validOrderFields(): array
    {
        $data = [];

        foreach (OrderingService::ALLOWED_ORDER_FIELDS as $field) {
            $data[] = [$field];
        }

        return $data;
    }
}
