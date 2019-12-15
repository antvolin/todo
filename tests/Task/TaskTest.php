<?php

namespace BeeJeeMVC\Tests\Text;

use BeeJeeMVC\Lib\AllowedStatuses;
use BeeJeeMVC\Model\Email;
use BeeJeeMVC\Model\Task;
use BeeJeeMVC\Model\Text;
use BeeJeeMVC\Model\UserName;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
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

    protected function setUp(): void
    {
        $this->userName = new UserName('test user name');
        $this->email = new Email('test@test.test');
        $this->text = new Text('test task text');

        $this->task = new Task($this->userName, $this->email, $this->text);
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

    /**
     * @test
     */
    public function shouldBeEditable(): void
    {
        $newText = 'new test text';
        $this->task->edit($newText);
        $this->assertEquals($newText, $this->task->getText());

        $editStatus = (new AllowedStatuses())->getEditStatus();
        $this->assertEquals($editStatus, $this->task->getStatus());
    }

    /**
     * @test
     */
    public function shouldBeDone(): void
    {
        $doneStatus = (new AllowedStatuses())->getDoneStatus();
        $this->task->done();
        $this->assertEquals($doneStatus, $this->task->getStatus());
    }
}
