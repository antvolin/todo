<?php

namespace BeeJeeMVC\Lib\Manager;

use BeeJeeMVC\Lib\Exceptions\CannotBeEmptyException;
use BeeJeeMVC\Lib\Exceptions\CannotDoneEntityException;
use BeeJeeMVC\Lib\Exceptions\CannotEditEntityException;
use BeeJeeMVC\Lib\Exceptions\ForbiddenStatusException;
use BeeJeeMVC\Lib\Exceptions\NotUniqueFieldsException;
use BeeJeeMVC\Lib\Exceptions\NotValidEmailException;
use BeeJeeMVC\Lib\Exceptions\NotFoundException;
use BeeJeeMVC\Lib\Repository\EntityRepositoryInterface;
use BeeJeeMVC\Model\Email;
use BeeJeeMVC\Model\EntityInterface;
use BeeJeeMVC\Model\Status;
use BeeJeeMVC\Model\Text;
use BeeJeeMVC\Model\UserName;
use BeeJeeMVC\Model\Id;

class EntityManager
{
    /**
     * @var string
     */
    private $entityName;

    /**
     * @var EntityRepositoryInterface
     */
    private $repository;

    /**
     * @param string $entityName
     * @param EntityRepositoryInterface $repository
     */
    public function __construct(string $entityName, EntityRepositoryInterface $repository)
    {
        $this->entityName = $entityName;
        $this->repository = $repository;
    }

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
    public function getById(int $id): EntityInterface
    {
        return $this->repository->getById($id);
    }

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
    public function getList(int $page, ?string $orderBy, ?string $order): array
    {
        return $this->repository->getList($page, $orderBy, $order);
    }

    /**
     * @return int
     */
    public function getCountRows(): int
    {
        return $this->repository->getCountRows();
    }

    /**
     * @param string $userName
     * @param string $email
     * @param string $text
     *
     * @throws CannotBeEmptyException
     * @throws NotValidEmailException
     * @throws ForbiddenStatusException
     * @throws NotUniqueFieldsException
     */
    public function save(string $userName, string $email, string $text): void
    {
        $this->repository->save(
            new $this->entityName(
                new Id(),
                new UserName($userName),
                new Email($email),
                new Text($text),
                new Status()
            )
        );
    }

    /**
     * @param int $entityId
     * @param string $text
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotUniqueFieldsException
     * @throws NotValidEmailException
     * @throws NotFoundException
     * @throws CannotEditEntityException
     */
    public function edit(int $entityId, string $text): void
    {
        $entity = $this->repository->getById($entityId);
        $entity->edit($text);
        $this->repository->save($entity, $entityId);
    }

    /**
     * @param int $entityId
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotUniqueFieldsException
     * @throws NotValidEmailException
     * @throws NotFoundException
     * @throws CannotDoneEntityException
     */
    public function done(int $entityId): void
    {
        $entity = $this->repository->getById($entityId);
        $entity->done();
        $this->repository->save($entity, $entity->getId()->getValue());
    }
}
