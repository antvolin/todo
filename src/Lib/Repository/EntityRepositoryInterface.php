<?php

namespace BeeJeeMVC\Lib\Repository;

use BeeJeeMVC\Lib\Exceptions\CannotBeEmptyException;
use BeeJeeMVC\Lib\Exceptions\ForbiddenStatusException;
use BeeJeeMVC\Lib\Exceptions\PdoErrorsException;
use BeeJeeMVC\Lib\Exceptions\NotValidEmailException;
use BeeJeeMVC\Lib\Exceptions\NotFoundException;
use BeeJeeMVC\Model\EntityInterface;

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
    public function getById(int $entityId): EntityInterface;

    /**
     * @return int
     */
    public function getCountRows(): int;

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
    public function getList(int $page, ?string $orderBy = null, ?string $order = null): array;

    /**
     * @param EntityInterface $entity
     * @param int|null $entityId
     *
     * @return int
     *
     * @throws PdoErrorsException
     */
    public function save(EntityInterface $entity, ?int $entityId = null): int;
}
