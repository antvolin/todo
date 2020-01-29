<?php

namespace Todo\Lib\Service\Entity;

use Todo\Lib\Exceptions\CannotDoneEntityException;
use Todo\Lib\Exceptions\CannotEditEntityException;
use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Repository\EntityRepositoryInterface;
use Todo\Model\EntityInterface;
use Todo\Model\Id;
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
    public function getById(Id $entityId): EntityInterface
    {
        return $this->repository->getById($entityId);
    }

    /**
     * @inheritDoc
     */
    public function getCollection(int $page, ?string $orderBy = null, ?string $order = null): array
    {
        return $this->repository->getCollection($page, $orderBy, $order);
    }

    /**
     * @inheritDoc
     */
    public function getCount(): int
    {
        return $this->repository->getCount();
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
    public function edit(Id $entityId, string $text): void
    {
        $entity = $this->repository->getById($entityId);

        if (Status::DONE === ((string) $entity->getStatus())) {
            throw new CannotEditEntityException();
        }

        $entity->setStatus(new Status(Status::EDITED));
        $entity->setText(new Text($text));

        $this->repository->add($entity);
    }

    /**
     * @inheritDoc
     */
    public function done(Id $entityId): void
    {
        $entity = $this->repository->getById($entityId);

        if (Status::DONE === ((string) $entity->getStatus())) {
            throw new CannotDoneEntityException();
        }

        $entity->setStatus(new Status(Status::DONE));

        $this->repository->add($entity);
    }

    /**
     * @inheritDoc
     */
    public function add(string $userName, string $email, string $text): Id
    {
        $entity = [
            'id' => null,
            'user_name' => $userName,
            'email' => $email,
            'text' => $text,
            'status' => null,
        ];

        $entity = $this->factory->create($entity);

        return $this->repository->add($entity);
    }

    /**
     * @inheritDoc
     */
    public function remove(Id $entityId): void
    {
        $this->repository->remove($entityId);
    }
}
