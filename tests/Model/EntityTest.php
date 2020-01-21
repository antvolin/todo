<?php

namespace Todo\Tests\Model;

use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\CannotDoneEntityException;
use Todo\Lib\Exceptions\CannotEditEntityException;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Lib\Exceptions\NotValidEmailException;
use Todo\Model\Email;
use Todo\Model\EntityInterface;
use Todo\Model\Id;
use Todo\Model\Status;
use Todo\Model\Entity;
use Todo\Model\Text;
use Todo\Model\UserName;
use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{
    /**
     * @var Id
     */
    protected $id;

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
     * @var Status
     */
    protected $status;

    /**
     * @var EntityInterface
     */
    protected $entity;

    /**
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     */
    protected function setUp(): void
    {
        $this->userName = new UserName('test user name');
        $this->email = new Email('test@test.test');
        $this->text = new Text('test text');
        $this->id = new Id();
        $this->status = new Status();
        $this->entity = new Entity($this->id, $this->userName, $this->email, $this->text, $this->status);
    }

    /**
     * @test
     */
    public function shouldBeCreated(): void
    {
        $this->assertEquals($this->id, $this->entity->getId());
        $this->assertEquals($this->userName, $this->entity->getUserName());
        $this->assertEquals($this->email, $this->entity->getEmail());
        $this->assertEquals($this->text, $this->entity->getText());
        $this->assertEquals($this->status, $this->entity->getStatus());
    }

    /**
     * @test
     *
     * @throws CannotEditEntityException
     * @throws ForbiddenStatusException
     * @throws CannotBeEmptyException
     */
    public function shouldBeEditable(): void
    {
        $newText = 'new test text';
        $this->entity->edit($newText);
        $this->assertEquals($newText, $this->entity->getText());
        $this->assertEquals(Status::EDITED, $this->entity->getStatus());
    }

    /**
     * @test
     *
     * @throws CannotEditEntityException
     * @throws ForbiddenStatusException
     * @throws CannotBeEmptyException
     */
    public function shouldBeNotEditableIfStatusDone(): void
    {
        $this->expectException(CannotEditEntityException::class);
        $this->entity->setStatus(new Status(Status::DONE));
        $this->entity->edit('new test text');
    }

    /**
     * @test
     *
     * @throws CannotDoneEntityException
     * @throws ForbiddenStatusException
     */
    public function shouldBeDone(): void
    {
        $this->entity->done();
        $this->assertEquals(Status::DONE, $this->entity->getStatus());
    }

    /**
     * @test
     *
     * @throws CannotDoneEntityException
     * @throws ForbiddenStatusException
     */
    public function shouldBeNotDoneIfStatusDone(): void
    {
        $this->expectException(CannotDoneEntityException::class);
        $this->entity->setStatus(new Status(Status::DONE));
        $this->entity->done();
    }
}
