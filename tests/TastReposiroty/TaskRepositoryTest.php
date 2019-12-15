<?php

namespace BeeJeeMVC\Tests\TaskRepository;

use BeeJeeMVC\Lib\TaskFileRepository;
use BeeJeeMVC\Model\Email;
use BeeJeeMVC\Model\Task;
use BeeJeeMVC\Model\Text;
use BeeJeeMVC\Model\UserName;
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
     * @var TaskFileRepository
     */
    protected $taskRepository;

    protected function setUp(): void
    {
        $this->userName = new UserName('test user name');
        $this->email = new Email('test@test.test');
        $this->text = new Text('test text');
        $this->taskRepository = new TaskFileRepository();

        $this->task = $this->taskRepository->save(new Task(new UserName($this->userName), new Email($this->email), new Text($this->text)));
    }

    /**
     * @test
     */
    public function shouldBeCreated(): void
    {
        $this->assertEquals($this->userName, $this->task->getUserName());
        $this->assertEquals($this->email, $this->task->getEmail());
        $this->assertEquals($this->text, $this->task->getText());
    }
}
