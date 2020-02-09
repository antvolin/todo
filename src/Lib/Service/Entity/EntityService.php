<?php

namespace Todo\Lib\Service\Entity;

use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\CannotDoneEntityException;
use Todo\Lib\Exceptions\CannotEditEntityException;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Repository\EntityRepositoryInterface;
use Todo\Lib\Service\Ordering\EntityOrderingService;
use Todo\Model\EntityInterface;
use Todo\Model\Id;
use Todo\Model\Status;
use Todo\Model\Text;

class EntityService implements EntityServiceInterface
{
    private EntityRepositoryInterface $repository;
    private EntityFactoryInterface $factory;

    public function __construct(EntityFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function getById(Id $entityId): EntityInterface
    {
        return $this->repository->getById($entityId);
    }

    public function getCollection(
        int $page,
        ?string $orderBy = null,
        ?string $order = null
    ): array
    {
        $collection = $this->repository->getCollection($page);

        if ($orderBy && $order) {
            $collection = (new EntityOrderingService())->orderCollection($collection, $orderBy, $order);
        }

        return $collection;
    }

    public function getCount(): int
    {
        return $this->repository->getCount();
    }

    public function setRepository(EntityRepositoryInterface $repository): void
    {
        $this->repository = $repository;
    }

    /**
     * @param Id $entityId
     * @param string $text
     *
     * @throws CannotBeEmptyException
     * @throws CannotDoneEntityException
     * @throws CannotEditEntityException
     * @throws ForbiddenStatusException
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
     * @param Id $entityId
     *
     * @throws CannotDoneEntityException
     * @throws ForbiddenStatusException
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

    public function remove(Id $entityId): void
    {
        $this->repository->remove($entityId);
    }
}
