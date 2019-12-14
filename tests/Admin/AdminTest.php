<?php

namespace BeeJeeMVC\Tests\Admin;

use BeeJeeMVC\Admin;
use BeeJeeMVC\AllowedStatuses;
use BeeJeeMVC\HashGenerator;
use BeeJeeMVC\TaskRepository;
use BeeJeeMVC\User;
use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeEditableTask(): void
    {
        $this->markTestSkipped('must be revisited.');

        $userName = 'test user name';
        $email = 'test@test.test';
        $text = 'test text';
        $newText = 'new test text';

        $user = new User();
        $task = $user->createTask($userName, $email, $text);

        $admin = new Admin();
        $admin->editTask($task, $newText);

        $taskRepo = new TaskRepository();
        $hash = (new HashGenerator())->generateHash($userName, $email, $text);
        $task = $taskRepo->getByHash($hash);

        $this->assertEquals($newText, $task->getText()->getValue());

        $statusEdit = (new AllowedStatuses())->getEditStatus();
        $this->assertEquals($statusEdit, $task->getStatus()->getValue());
    }

    /**
     * @test
     */
    public function shouldBeDoneTask(): void
    {
        $this->markTestSkipped('must be revisited.');

        $userName = 'test user name';
        $email = 'test@test.test';
        $text = 'test text';

        $user = new User();
        $task = $user->createTask($userName, $email, $text);

        $admin = new Admin();
        $admin->doneTask($task);

        $taskRepo = new TaskRepository();
        $hash = (new HashGenerator())->generateHash($userName, $email, $text);
        $task = $taskRepo->getByHash($hash);

        $statusDone = (new AllowedStatuses())->getEditStatus();
        $this->assertEquals($statusDone, $task->getStatus()->getValue());
    }
}
