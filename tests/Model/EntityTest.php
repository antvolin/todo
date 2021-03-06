<?php

namespace Tests\Model;

use PHPUnit\Framework\TestCase;
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

class EntityTest extends TestCase
{
    private Id $id;
    private UserName $userName;
    private Email $email;
    private Text $text;
    private Status $status;
    private EntityInterface $entity;

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
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws CannotDoneEntityException
     * @throws CannotEditEntityException
     */
    public function shouldBeEditable(): void
    {
        $newText = 'new test text';
        $this->entity->setText(new Text($newText));
        $this->entity->setStatus(new Status(Status::EDITED));
        $this->assertEquals($newText, $this->entity->getText());
        $this->assertEquals(Status::EDITED, $this->entity->getStatus());
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws CannotDoneEntityException
     * @throws ForbiddenStatusException
     * @throws CannotEditEntityException
     */
    public function shouldBeNotEditableIfStatusDone(): void
    {
        $this->expectException(CannotEditEntityException::class);
        $this->entity->setStatus(new Status(Status::DONE));
        $this->entity->setText(new Text('new test text'));
    }

    /**
     * @test
     *
     * @throws CannotDoneEntityException
     * @throws ForbiddenStatusException
     */
    public function shouldBeDone(): void
    {
        $this->entity->setStatus(new Status(Status::DONE));
        $this->assertEquals(Status::DONE, $this->entity->getStatus());
    }

    /**
     * @test
     *
     * @throws ForbiddenStatusException
     * @throws CannotDoneEntityException
     */
    public function shouldBeNotDoneIfStatusDone(): void
    {
        $this->expectException(CannotDoneEntityException::class);
        $this->entity->setStatus(new Status(Status::DONE));
        $this->entity->setStatus(new Status(Status::DONE));
    }

    /**
     * @test
     */
    public function shouldBeSerializableToJson(): void
    {
        $json = $this->entity->jsonSerialize();

        $this->assertArrayHasKey('id', $json);
        $this->assertIsString($json['userName']);
        $this->assertNotEmpty($json['userName']);
        $this->assertIsString($json['email']);
        $this->assertNotEmpty($json['email']);
        $this->assertIsString($json['text']);
        $this->assertNotEmpty($json['text']);
        $this->assertArrayHasKey('status', $json);
    }
}
