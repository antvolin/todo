<?php

namespace BeeJeeMVC\Model;

use BeeJeeMVC\Lib\Exceptions\CannotBeEmptyException;
use BeeJeeMVC\Lib\Exceptions\CannotDoneEntityException;
use BeeJeeMVC\Lib\Exceptions\CannotEditEntityException;
use BeeJeeMVC\Lib\Exceptions\ForbiddenStatusException;

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
     */
    public function setStatus(?Status $status): void;

    /**
     * @param string $text
     *
     * @throws CannotEditEntityException
     * @throws ForbiddenStatusException
     * @throws CannotBeEmptyException
     */
    public function edit(string $text): void;

    /**
     * @throws CannotDoneEntityException
     * @throws ForbiddenStatusException
     */
    public function done(): void;
}
