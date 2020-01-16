<?php

namespace BeeJeeMVC\Lib\Service;

use BeeJeeMVC\Lib\Repository\EntityRepositoryInterface;
use BeeJeeMVC\Model\Email;
use BeeJeeMVC\Model\EntityInterface;
use BeeJeeMVC\Model\Status;
use BeeJeeMVC\Model\Text;
use BeeJeeMVC\Model\UserName;
use BeeJeeMVC\Model\Id;

class EntityService implements EntityServiceInterface
{
    /**
     * @var string
     */
    private $entityClass;

    /**
     * @var EntityRepositoryInterface
     */
    private $repository;

    /**
     * @inheritDoc
     */
    public function __construct(string $entityClass, EntityRepositoryInterface $repository)
    {
        $this->entityClass = $entityClass;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function getEntityById(int $id): EntityInterface
    {
        return $this->repository->getEntityById($id);
    }

    /**
     * @inheritDoc
     */
    public function getEntities(int $page, ?string $orderBy = null, ?string $order = null): array
    {
        return $this->repository->getEntities($page, $orderBy, $order);
    }

    /**
     * @inheritDoc
     */
    public function getCountEntities(): int
    {
        return $this->repository->getCountEntities();
    }

    /**
     * @inheritDoc
     */
    public function saveEntity(string $userName, string $email, string $text): int
    {
        return $this->repository->saveEntity(
            new $this->entityClass(
                new Id(),
                new UserName($userName),
                new Email($email),
                new Text($text),
                new Status()
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function deleteEntity(int $entityId): void
    {
        $this->repository->deleteEntity($entityId);
    }

    /**
     * @inheritDoc
     */
    public function editEntity(int $entityId, string $text): void
    {
        $entity = $this->repository->getEntityById($entityId);
        $entity->edit($text);
        $this->repository->saveEntity($entity, $entityId);
    }

    /**
     * @inheritDoc
     */
    public function doneEntity(int $entityId): void
    {
        $entity = $this->repository->getEntityById($entityId);
        $entity->done();
        $this->repository->saveEntity($entity, $entity->getId()->getValue());
    }
}
