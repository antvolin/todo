<?php

namespace BeeJeeMVC\Model;

use BeeJeeMVC\Lib\Exceptions\CannotDoneTaskException;
use BeeJeeMVC\Lib\Exceptions\CannotEditTaskException;

class Task
{
    /**
     * @var int
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
     * @param UserName $userName
     * @param Email $email
     * @param Text $text
     */
    public function __construct(UserName $userName, Email $email, Text $text)
    {
        $this->userName = $userName;
        $this->email = $email;
        $this->text = $text;
    }

    /**
     * @return int
     */
    public function getId(): int
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
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
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
        if (Status::STATUS_DONE == $this->status) {
            throw new CannotEditTaskException();
        }

        $this->status = new Status(Status::STATUS_EDITED);
        $this->text = new Text($text);
    }

    /**
     * @throws CannotDoneTaskException
     */
    public function done(): void
    {
        if (Status::STATUS_DONE == $this->status) {
            throw new CannotDoneTaskException();
        }

        $this->status = new Status(Status::STATUS_DONE);
    }
}
