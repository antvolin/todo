<?php

namespace BeeJeeMVC\Tests\TaskRepository;

use BeeJeeMVC\AllowedStatuses;
use BeeJeeMVC\Email;
use BeeJeeMVC\HashGenerator;
use BeeJeeMVC\Task;
use BeeJeeMVC\TaskRepository;
use BeeJeeMVC\Text;
use BeeJeeMVC\UserName;
use PHPUnit\Framework\TestCase;

class TaskRepositoryTest extends TestCase
{
    /**
     * @var UserName
     */
    protected $userName;

    /**
     * @var Email
     */
    protected $email;

    /**
     * @var Text
     */
    protected $text;

    /**
     * @var Task
     */
    protected $task;

    /**
     * @var string
     */
    protected $hash;

    /**
     * @var TaskRepository
     */
    protected $taskRepository;

    protected function setUp(): void
    {
        $this->userName = new UserName('test user name');
        $this->email = new Email('test@test.test');
        $this->text = new Text('test text');
        $this->taskRepository = new TaskRepository();

        $userName = $this->userName->getValue();
        $email = $this->email->getValue();
        $text = $this->text->getValue();

        $this->task = $this->taskRepository->create($this->userName, $this->email, $this->text);
        $this->hash = (new HashGenerator())->generateHash($userName, $email, $text);
    }

    /**
     * @test
     */
    public function shouldBeCreated(): void
    {
        $this->assertEquals($this->userName->getValue(), $this->task->getUserName()->getValue());
        $this->assertEquals($this->email->getValue(), $this->task->getEmail()->getValue());
        $this->assertEquals($this->text->getValue(), $this->task->getText()->getValue());
    }

    /**
     * @test
     */
    public function shouldBeEdited(): void
    {
        $newText = 'test new task text';
        $this->task->edit($newText);
        $task = $this->taskRepository->getByHash($this->hash);
        $this->assertEquals($newText, $task->getText()->getValue());

        $statusEdit = (new AllowedStatuses())->getEditStatus();
        $this->assertEquals($statusEdit, $task->getStatus()->getValue());
    }

    /**
     * @test
     */
    public function shouldBeDone(): void
    {
        $this->task->done();
        $task = $this->taskRepository->getByHash($this->hash);
        $statusDone = (new AllowedStatuses())->getDoneStatus();

        $this->assertEquals($statusDone, $task->getStatus()->getValue());
    }
}
