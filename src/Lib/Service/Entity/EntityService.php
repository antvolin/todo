<?php

namespace Todo\Lib\Service\Entity;

use Todo\Lib\Exceptions\CannotDoneEntityException;
use Todo\Lib\Exceptions\CannotEditEntityException;
use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Repository\EntityRepositoryInterface;
use Todo\Model\EntityInterface;
use Todo\Model\Status;
use Todo\Model\Text;

class EntityService implements EntityServiceInterface
{
    /**
     * @var EntityRepositoryInterface $repository
     */
    private $repository;

    /**
     * @var EntityFactoryInterface $factory
     */
    private $factory;

    /**
     * @inheritDoc
     */
    public function __construct(EntityFactoryInterface $factory)
    {
        $this->factory = $factory;
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
        return $this->factory->create($entity);
    }

    /**
     * @inheritDoc
     */
    public function editEntity(int $entityId, string $text): void
    {
        $entity = $this->repository->getEntityById($entityId);

        if (Status::DONE === ((string) $entity->getStatus())) {
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

        if (Status::DONE === ((string) $entity->getStatus())) {
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
        $entity = [
            'id' => null,
            'user_name' => $userName,
            'email' => $email,
            'text' => $text,
            'status' => null,
        ];

        $entity = $this->factory->create($entity);

        return $this->repository->addEntity($entity);
    }

    /**
     * @inheritDoc
     */
    public function deleteEntity(int $entityId): void
    {
        $this->repository->deleteEntity($entityId);
    }
}
