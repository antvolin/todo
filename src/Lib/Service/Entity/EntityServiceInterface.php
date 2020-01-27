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
     * @param EntityRepositoryInterface $repository
     * @param int $id
     *
     * @return EntityInterface
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     * @throws EntityNotFoundException
     */
    public function getEntityById(EntityRepositoryInterface $repository, int $id): EntityInterface;

    /**
     * @param EntityRepositoryInterface $repository
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
    public function getEntities(EntityRepositoryInterface $repository, int $page, ?string $orderBy = null, ?string $order = null): array;

    /**
     * @param EntityRepositoryInterface $repository
     *
     * @return int
     */
    public function getCountEntities(EntityRepositoryInterface $repository): int;

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
     * @param EntityRepositoryInterface $repository
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
    public function editEntity(EntityRepositoryInterface $repository, int $entityId, string $text): void;

    /**
     * @param EntityRepositoryInterface $repository
     * @param int $entityId
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws PdoErrorsException
     * @throws NotValidEmailException
     * @throws EntityNotFoundException
     * @throws CannotDoneEntityException
     */
    public function doneEntity(EntityRepositoryInterface $repository, int $entityId): void;

    /**
     * @param EntityRepositoryInterface $repository
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
    public function addEntity(EntityRepositoryInterface $repository, string $userName, string $email, string $text): int;

    /**
     * @param EntityRepositoryInterface $repository
     * @param int $entityId
     */
    public function deleteEntity(EntityRepositoryInterface $repository, int $entityId): void;
}
