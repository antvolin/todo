<?php

namespace Todo\Model;

use JsonSerializable;
use Todo\Lib\Exceptions\CannotDoneEntityException;
use Todo\Lib\Exceptions\CannotEditEntityException;

class Entity implements EntityInterface, JsonSerializable
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
     * @inheritdoc
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
     * @return array
     */
    public function jsonSerialize(): array
    {
        $status = $this->getStatus();

        return [
            'id' => $this->getId()->getValue(),
            'userName' => $this->getUserName()->getValue(),
            'email' => $this->getEmail()->getValue(),
            'text' => $this->getText()->getValue(),
            'status' => $status ? $status->getValue() : null,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function getText(): Text
    {
        return $this->text;
    }

    /**
     * @inheritdoc
     */
    public function getStatus(): ?Status
    {
        return $this->status;
    }

    /**
     * @inheritdoc
     */
    public function setStatus(?Status $status): void
    {
        $this->status = $status;
    }

    /**
     * @inheritdoc
     */
    public function edit(string $text): void
    {
        if (Status::DONE == $this->status) {
            throw new CannotEditEntityException();
        }

        $this->status = new Status(Status::EDITED);
        $this->text = new Text($text);
    }

    /**
     * @inheritdoc
     */
    public function done(): void
    {
        if (Status::DONE == $this->status) {
            throw new CannotDoneEntityException();
        }

        $this->status = new Status(Status::DONE);
    }
}
