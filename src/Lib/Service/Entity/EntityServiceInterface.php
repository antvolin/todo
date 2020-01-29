<?php

namespace Todo\Lib\Service\Entity;

use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\CannotDoneEntityException;
use Todo\Lib\Exceptions\CannotEditEntityException;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Lib\Exceptions\PdoErrorsException;
use Todo\Lib\Exceptions\NotValidEmailException;
use Todo\Lib\Exceptions\EntityNotFoundException;
use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Repository\EntityRepositoryInterface;
use Todo\Model\EntityInterface;
use Todo\Model\Id;

interface EntityServiceInterface
{
    /**
     * @param EntityFactoryInterface $factory
     */
    public function __construct(EntityFactoryInterface $factory);

    /**
     * @param Id $id
     *
     * @return EntityInterface
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     * @throws EntityNotFoundException
     */
    public function getById(Id $id): EntityInterface;

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
    public function getCollection(int $page, ?string $orderBy = null, ?string $order = null): array;

    /**
     * @return int
     */
    public function getCount(): int;

    /**
     * @param EntityRepositoryInterface $repository
     */
    public function setRepository(EntityRepositoryInterface $repository): void;

    /**
     * @param Id $entityId
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
    public function edit(Id $entityId, string $text): void;

    /**
     * @param Id $entityId
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws PdoErrorsException
     * @throws NotValidEmailException
     * @throws EntityNotFoundException
     * @throws CannotDoneEntityException
     */
    public function done(Id $entityId): void;

    /**
     * @param string $userName
     * @param string $email
     * @param string $text
     *
     * @return Id
     *
     * @throws CannotBeEmptyException
     * @throws NotValidEmailException
     * @throws ForbiddenStatusException
     * @throws PdoErrorsException
     */
    public function add(string $userName, string $email, string $text): Id;

    /**
     * @param Id $entityId
     */
    public function remove(Id $entityId): void;
}
