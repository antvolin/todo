<?php

namespace Todo\Model;

use JsonSerializable;
use Todo\Lib\Exceptions\CannotDoneEntityException;
use Todo\Lib\Exceptions\CannotEditEntityException;

class Entity implements EntityInterface, JsonSerializable
{
    private Id $id;
    private UserName $userName;
    private Email $email;
    private Text $text;
    private Status $status;

    public function __construct(
        Id $id,
        UserName $userName,
        Email $email,
        Text $text,
        Status $status
    )
    {
        $this->id = $id;
        $this->userName = $userName;
        $this->email = $email;
        $this->text = $text;
        $this->status = $status;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getUserName(): UserName
    {
        return $this->userName;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getText(): Text
    {
        return $this->text;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): void
    {
        if (Status::DONE === ((string) $this->status)) {
            throw new CannotDoneEntityException();
        }

        $this->status = $status;
    }

    public function setText(Text $text): void
    {
        if (Status::DONE === ((string) $this->status)) {
            throw new CannotEditEntityException();
        }

        $this->text = $text;
    }

    public function setId(Id $id): void
    {
        $this->id = $id;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId()->getValue(),
            'userName' => (string) $this->getUserName(),
            'email' => (string) $this->getEmail(),
            'text' => (string) $this->getText(),
            'status' => (string) $this->getStatus(),
        ];
    }
}
