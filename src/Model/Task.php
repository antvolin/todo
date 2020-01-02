<?php

namespace BeeJeeMVC\Model;

use BeeJeeMVC\Lib\Exceptions\CannotDoneTaskException;
use BeeJeeMVC\Lib\Exceptions\CannotEditTaskException;

class Task
{
    /**
     * @var Id
     */
    private $id;

    /**
     * @var UserName
     */
    private $userName;

    /**
     * @var Email
     */
    private $email;

    /**
     * @var Text
     */
    private $text;

    /**
     * @var Status
     */
    private $status;

    /**
     * @param Id $id
     * @param UserName $userName
     * @param Email $email
     * @param Text $text
     * @param Status $status
     */
    public function __construct(Id $id, UserName $userName, Email $email, Text $text, Status $status)
    {
        $this->id = $id;
        $this->userName = $userName;
        $this->email = $email;
        $this->text = $text;
        $this->status = $status;
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return UserName
     */
    public function getUserName(): UserName
    {
        return $this->userName;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return Text
     */
    public function getText(): Text
    {
        return $this->text;
    }

    /**
     * @return Status|null
     */
    public function getStatus(): ?Status
    {
        return $this->status;
    }

    /**
     * @param Status|null $status
     */
    public function setStatus(?Status $status): void
    {
        $this->status = $status;
    }

    /**
     * @param string $text
     *
     * @throws CannotEditTaskException
     */
    public function edit(string $text): void
    {
        if (Status::DONE == $this->status) {
            throw new CannotEditTaskException();
        }

        $this->status = new Status(Status::EDITED);
        $this->text = new Text($text);
    }

    /**
     * @throws CannotDoneTaskException
     */
    public function done(): void
    {
        if (Status::DONE == $this->status) {
            throw new CannotDoneTaskException();
        }

        $this->status = new Status(Status::DONE);
    }
}
