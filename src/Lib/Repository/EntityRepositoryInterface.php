<?php

namespace Todo\Lib\Repository;

use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Lib\Exceptions\PdoErrorsException;
use Todo\Lib\Exceptions\NotValidEmailException;
use Todo\Lib\Exceptions\NotFoundException;
use Todo\Model\EntityInterface;

interface EntityRepositoryInterface
{
    /**
     * @param int $entityId
     *
     * @return EntityInterface
     *
     * @throws NotFoundException
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     */
    public function getEntityById(int $entityId): EntityInterface;

    /**
     * @return int
     */
    public function getCountEntities(): int;

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
     * @param EntityInterface $entity
     * @param int|null $entityId
     *
     * @return int
     *
     * @throws PdoErrorsException
     */
    public function addEntity(EntityInterface $entity, ?int $entityId = null): int;

    /**
     * @param int $entityId
     */
    public function deleteEntity(int $entityId): void;
}
