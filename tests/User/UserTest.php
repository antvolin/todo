<?php

namespace BeeJeeMVC\Tests\User;

use BeeJeeMVC\HashGenerator;
use BeeJeeMVC\TaskRepository;
use BeeJeeMVC\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeCreatableTask(): void
    {
        $this->markTestSkipped('must be revisited.');

        $userName = 'test user name';
        $email = 'test@test.test';
        $text = 'test text';

        $user = new User();
        $user->createTask($userName, $email, $text);

        $taskRepo = new TaskRepository();
        $taskList = $user->getTaskList();
        $hash = (new HashGenerator())->generateHash($userName, $email, $text);
        $this->assertSame($taskRepo->getByHash($hash), $taskList[$hash]);
    }
}
