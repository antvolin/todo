<?php

namespace Todo\Model;

use Todo\Lib\Exceptions\CannotDoneEntityException;
use Todo\Lib\Exceptions\CannotEditEntityException;

interface EntityInterface
{
    public function __construct(Id $id, UserName $userName, Email $email, Text $text, Status $status);

    public function getId(): Id;

    public function getUserName(): UserName;

    public function getEmail(): Email;

    public function getText(): Text;

    public function getStatus(): Status;

    /**
     * @param Status $status
     *
     * @throws CannotDoneEntityException
     */
    public function setStatus(Status $status): void;

    /**
     * @param Text $text
     *
     * @throws CannotEditEntityException
     */
    public function setText(Text $text): void;

    public function setId(Id $id): void;
}
