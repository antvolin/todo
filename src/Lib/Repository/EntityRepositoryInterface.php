<?php

namespace Todo\Lib\Repository;

use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Lib\Exceptions\PdoErrorsException;
use Todo\Lib\Exceptions\NotValidEmailException;
use Todo\Lib\Exceptions\EntityNotFoundException;
use Todo\Model\EntityInterface;

interface EntityRepositoryInterface
{
    /**
     * @param int $entityId
     *
     * @return EntityInterface
     *
     * @throws EntityNotFoundException
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     */
    public function getById(int $entityId): EntityInterface;

    /**
     * @param int $page
     * @param string|null $orderBy
     * @param string|null $order
     *
     * @return EntityInterface[]
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     */
    public function getCollection(int $page, ?string $orderBy = null, ?string $order = null): array;

    /**
     * @return int
     */
    public function getCount(): int;

    /**
     * @param EntityInterface $entity
     *
     * @return int
     *
     * @throws PdoErrorsException
     */
    public function add(EntityInterface $entity): int;

    /**
     * @param int $entityId
     */
    public function remove(int $entityId): void;
}
