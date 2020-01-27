<?php

namespace Todo\Lib\Service\Entity;

use Todo\Lib\Exceptions\CannotDoneEntityException;
use Todo\Lib\Exceptions\CannotEditEntityException;
use Todo\Lib\Repository\EntityRepositoryInterface;
use Todo\Model\Email;
use Todo\Model\EntityInterface;
use Todo\Model\Status;
use Todo\Model\Text;
use Todo\Model\UserName;
use Todo\Model\Id;

class EntityService implements EntityServiceInterface
{
    /**
     * @var string
     */
    private $entityClass;

    /**
     * @var string
     */
    private $entityName;

    /**
     * @var EntityRepositoryInterface $repository
     */
    private $repository;

    /**
     * @inheritDoc
     */
    public function __construct(string $entityClassNamespace, string $entityName)
    {
        $this->entityName = strtolower($entityName);
        $this->entityClass = $entityClassNamespace.ucfirst($this->entityName);
    }

    /**
     * @inheritDoc
     */
    public function getEntityName(): string
    {
        return $this->entityName;
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
    public function setRepository(EntityRepositoryInterface $repository): void
    {
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function createEntity(array $entity): EntityInterface
    {
        $entityClass = $this->entityClass;

        return new $entityClass(
            new Id($entity['id']),
            new UserName($entity['user_name']),
            new Email($entity['email']),
            new Text($entity['text']),
            new Status($entity['status'])
        );
    }

    /**
     * @inheritDoc
     */
    public function editEntity(int $entityId, string $text): void
    {
        $entity = $this->repository->getEntityById($entityId);

        if (Status::DONE == $entity->getStatus()) {
            throw new CannotEditEntityException();
        }

        $entity->setStatus(new Status(Status::EDITED));
        $entity->setText(new Text($text));

        $this->repository->addEntity($entity, $entityId);
    }

    /**
     * @inheritDoc
     */
    public function doneEntity(int $entityId): void
    {
        $entity = $this->repository->getEntityById($entityId);

        if (Status::DONE == $entity->getStatus()) {
            throw new CannotDoneEntityException();
        }

        $entity->setStatus(new Status(Status::DONE));

        $this->repository->addEntity($entity, $entity->getId()->getValue());
    }

    /**
     * @inheritDoc
     */
    public function addEntity(string $userName, string $email, string $text): int
    {
        return $this->repository->addEntity(
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
}
