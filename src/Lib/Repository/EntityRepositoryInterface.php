<?php

namespace Todo\Lib\Repository;

use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Lib\Exceptions\NotValidEmailException;
use Todo\Lib\Exceptions\EntityNotFoundException;
use Todo\Model\EntityInterface;
use Todo\Model\Id;

interface EntityRepositoryInterface
{
    /**
     * @param Id $entityId
     *
     * @return EntityInterface
     *
     * @throws EntityNotFoundException
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     */
    public function getById(Id $entityId): EntityInterface;

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
     * @return Id
     */
    public function add(EntityInterface $entity): Id;

    /**
     * @param Id $entityId
     */
    public function remove(Id $entityId): void;
}
