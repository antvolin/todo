<?php

namespace BeeJeeMVC\Tests\Text;

use BeeJeeMVC\AllowedStatuses;
use BeeJeeMVC\Email;
use BeeJeeMVC\Task;
use BeeJeeMVC\Text;
use BeeJeeMVC\UserName;
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
        $this->assertEquals($this->userName->getValue(), $this->task->getUserName()->getValue());
        $this->assertEquals($this->email->getValue(), $this->task->getEmail()->getValue());
        $this->assertEquals($this->text->getValue(), $this->task->getText()->getValue());
        $createdStatus = (new AllowedStatuses())->getCreatedStatus();
        $this->assertEquals($createdStatus, $this->task->getStatus()->getValue());
    }

    /**
     * @test
     */
    public function shouldBeEditable(): void
    {
        $newText = 'new test text';
        $this->task->edit($newText);
        $this->assertEquals($newText, $this->task->getText()->getValue());

        $editStatus = (new AllowedStatuses())->getEditStatus();
        $this->assertEquals($editStatus, $this->task->getStatus()->getValue());
    }

    /**
     * @test
     */
    public function shouldBeDone(): void
    {
        $doneStatus = (new AllowedStatuses())->getDoneStatus();
        $this->task->done();
        $this->assertEquals($doneStatus, $this->task->getStatus()->getValue());
    }
}
