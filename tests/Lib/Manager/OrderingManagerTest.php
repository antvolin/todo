<?php

namespace BeeJeeMVC\Tests\Lib\Manager;

use BeeJeeMVC\Lib\Manager\OrderingManager;
use PHPUnit\Framework\TestCase;

class OrderingManagerTest extends TestCase
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
        $this->assertEquals('user_name', OrderingManager::getOrderBy(null));
        $this->assertEquals('user_name', OrderingManager::getOrderBy(''));
        $this->assertEquals('user_name', OrderingManager::getOrderBy('test'));
        $this->assertEquals($field, OrderingManager::getOrderBy($field));
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
        $this->assertEquals(OrderingManager::ASC, OrderingManager::getOrder(null));
        $this->assertEquals(OrderingManager::ASC, OrderingManager::getOrder(''));
        $this->assertEquals(OrderingManager::ASC, OrderingManager::getOrder('test'));
        $this->assertEquals($field, OrderingManager::getOrder($field));
    }

    /**
     * @test
     */
    public function shouldBeGettingValidNextOrder(): void
    {
        $this->assertEquals(OrderingManager::DESC, OrderingManager::getNextOrder(null));
        $this->assertEquals(OrderingManager::DESC, OrderingManager::getNextOrder(OrderingManager::ASC));
        $this->assertEquals(OrderingManager::ASC, OrderingManager::getNextOrder(OrderingManager::DESC));
        $this->assertEquals(OrderingManager::ASC, OrderingManager::getNextOrder('test'));
    }

    /**
     * @return array
     */
    public function validOrderByFields(): array
    {
        $data = [];

        foreach (OrderingManager::ALLOWED_ORDER_BY_FIELDS as $field) {
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

        foreach (OrderingManager::ALLOWED_ORDER_FIELDS as $field) {
            $data[] = [$field];
        }

        return $data;
    }
}
