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

    /**
     * @inheritdoc
     */
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
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @inheritdoc
     */
    public function setStatus(Status $status): void
    {
        if (Status::DONE === ((string) $this->status)) {
            throw new CannotDoneEntityException();
        }

        $this->status = $status;
    }

    /**
     * @inheritdoc
     */
    public function setText(Text $text): void
    {
        if (Status::DONE === ((string) $this->status)) {
            throw new CannotEditEntityException();
        }

        $this->text = $text;
    }

    /**
     * @inheritdoc
     */
    public function setId(Id $id): void
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
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
