<?php

namespace BeeJeeMVC\Tests\Lib\Factory\Repository;

use BeeJeeMVC\Lib\Factory\Repository\TaskPdoRepositoryFactory;
use BeeJeeMVC\Lib\Repository\TaskPdoRepository;
use PHPUnit\Framework\TestCase;

class TaskPdoRepositoryFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeCreatedTaskPdoRepository(): void
    {
        $taskPdoRepository = (new TaskPdoRepositoryFactory())->create();

        $this->assertInstanceOf(TaskPdoRepository::class, $taskPdoRepository);
        $this->assertTrue(method_exists($taskPdoRepository, 'getById'));
        $this->assertTrue(method_exists($taskPdoRepository, 'getCountRows'));
        $this->assertTrue(method_exists($taskPdoRepository, 'getList'));
        $this->assertTrue(method_exists($taskPdoRepository, 'save'));
    }
}
