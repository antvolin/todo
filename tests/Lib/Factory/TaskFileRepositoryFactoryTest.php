<?php

namespace BeeJeeMVC\Tests\Lib;

use BeeJeeMVC\Lib\Factory\TaskFileRepositoryFactory;
use BeeJeeMVC\Lib\Repository\TaskFileRepository;
use PHPUnit\Framework\TestCase;

class TaskFileRepositoryFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeCreatedTaskFileRepository(): void
    {
        $taskPdoRepository = (new TaskFileRepositoryFactory())->create();

        $this->assertInstanceOf(TaskFileRepository::class, $taskPdoRepository);
        $this->assertTrue(method_exists($taskPdoRepository, 'getById'));
        $this->assertTrue(method_exists($taskPdoRepository, 'getCountRows'));
        $this->assertTrue(method_exists($taskPdoRepository, 'getList'));
        $this->assertTrue(method_exists($taskPdoRepository, 'save'));
    }
}
