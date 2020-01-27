<?php

namespace Todo\Lib\Service\Entity;

use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\CannotDoneEntityException;
use Todo\Lib\Exceptions\CannotEditEntityException;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Lib\Exceptions\PdoErrorsException;
use Todo\Lib\Exceptions\NotValidEmailException;
use Todo\Lib\Exceptions\EntityNotFoundException;
use Todo\Lib\Repository\EntityRepositoryInterface;
use Todo\Model\EntityInterface;

interface EntityServiceInterface
{
    /**
     * @param string $entityClassNamespace
     * @param string $entityName
     */
    public function __construct(string $entityClassNamespace, string $entityName);

    /**
     * @return string
     */
    public function getEntityName(): string;

    /**
     * @param int $id
     *
     * @return EntityInterface
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     * @throws EntityNotFoundException
     */
    public function getEntityById(int $id): EntityInterface;

    /**
     * @param int $page
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
     * @param EntityRepositoryInterface $repository
     */
    public function setRepository(EntityRepositoryInterface $repository): void;

    /**
     * @param array $entity
     *
     * @return EntityInterface
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     */
    public function createEntity(array $entity): EntityInterface;

    /**
     * @param int $entityId
     * @param string $text
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws PdoErrorsException
     * @throws NotValidEmailException
     * @throws EntityNotFoundException
     * @throws CannotEditEntityException
     * @throws CannotDoneEntityException
     */
    public function editEntity(int $entityId, string $text): void;

    /**
     * @param int $entityId
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws PdoErrorsException
     * @throws NotValidEmailException
     * @throws EntityNotFoundException
     * @throws CannotDoneEntityException
     */
    public function doneEntity(int $entityId): void;

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
    public function addEntity(string $userName, string $email, string $text): int;

    /**
     * @param int $entityId
     */
    public function deleteEntity(int $entityId): void;
}
