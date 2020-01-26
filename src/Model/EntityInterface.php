<?php

namespace Todo\Model;

use Todo\Lib\Exceptions\CannotDoneEntityException;
use Todo\Lib\Exceptions\CannotEditEntityException;

interface EntityInterface
{
    public const ALLOWED_ENTITY_NAMES = [
        'entity',
    ];

    /**
     * @param Id $id
     * @param UserName $userName
     * @param Email $email
     * @param Text $text
     * @param Status $status
     */
    public function __construct(Id $id, UserName $userName, Email $email, Text $text, Status $status);

    /**
     * @return Id
     */
    public function getId(): Id;

    /**
     * @return UserName
     */
    public function getUserName(): UserName;

    /**
     * @return Email
     */
    public function getEmail(): Email;

    /**
     * @return Text
     */
    public function getText(): Text;

    /**
     * @return Status|null
     */
    public function getStatus(): ?Status;

    /**
     * @param Status|null $status
     *
     * @throws CannotDoneEntityException
     */
    public function setStatus(?Status $status): void;

    /**
     * @param Text $text
     *
     * @throws CannotEditEntityException
     */
    public function setText(Text $text): void;
}
