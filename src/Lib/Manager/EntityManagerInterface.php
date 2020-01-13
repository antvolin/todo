<?php

namespace BeeJeeMVC\Lib\Manager;

use BeeJeeMVC\Lib\Exceptions\CannotBeEmptyException;
use BeeJeeMVC\Lib\Exceptions\CannotDoneEntityException;
use BeeJeeMVC\Lib\Exceptions\CannotEditEntityException;
use BeeJeeMVC\Lib\Exceptions\ForbiddenStatusException;
use BeeJeeMVC\Lib\Exceptions\PdoErrorsException;
use BeeJeeMVC\Lib\Exceptions\NotValidEmailException;
use BeeJeeMVC\Lib\Exceptions\NotFoundException;
use BeeJeeMVC\Lib\Repository\EntityRepositoryInterface;
use BeeJeeMVC\Model\EntityInterface;

interface EntityManagerInterface
{
    /**
     * @param string $entityClass
     * @param EntityRepositoryInterface $repository
     */
    public function __construct(string $entityClass, EntityRepositoryInterface $repository);

    /**
     * @param int $id
     *
     * @return EntityInterface
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     * @throws NotFoundException
     */
    public function getEntityById(int $id): EntityInterface;

    /**
     * @param int $page
     *
     * @param string|null $orderBy
     * @param string|null $order
     *
     * @return array
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     */
    public function getEntities(int $page, ?string $orderBy = null, ?string $order = null): array;

    /**
     * @return int
     */
    public function getCountEntities(): int;

    /**
     * @param string $userName
     * @param string $email
     * @param string $text
     *
     * @return int
     *
     * @throws CannotBeEmptyException
     * @throws NotValidEmailException
     * @throws ForbiddenStatusException
     * @throws PdoErrorsException
     */
    public function saveEntity(string $userName, string $email, string $text): int;

    /**
     * @param int $entityId
     */
    public function deleteEntity(int $entityId): void;

    /**
     * @param int $entityId
     * @param string $text
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws PdoErrorsException
     * @throws NotValidEmailException
     * @throws NotFoundException
     * @throws CannotEditEntityException
     */
    public function editEntity(int $entityId, string $text): void;

    /**
     * @param int $entityId
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws PdoErrorsException
     * @throws NotValidEmailException
     * @throws NotFoundException
     * @throws CannotDoneEntityException
     */
    public function doneEntity(int $entityId): void;
}
