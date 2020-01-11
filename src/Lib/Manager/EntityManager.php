<?php

namespace BeeJeeMVC\Lib\Manager;

use BeeJeeMVC\Lib\Repository\EntityRepositoryInterface;
use BeeJeeMVC\Model\Email;
use BeeJeeMVC\Model\EntityInterface;
use BeeJeeMVC\Model\Status;
use BeeJeeMVC\Model\Text;
use BeeJeeMVC\Model\UserName;
use BeeJeeMVC\Model\Id;

class EntityManager implements EntityManagerInterface
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
     * @inheritDoc
     */
    public function __construct(string $entityName, EntityRepositoryInterface $repository)
    {
        $this->entityName = $entityName;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): EntityInterface
    {
        return $this->repository->getById($id);
    }

    /**
     * @inheritDoc
     */
    public function getList(int $page, ?string $orderBy, ?string $order): array
    {
        return $this->repository->getList($page, $orderBy, $order);
    }

    /**
     * @inheritDoc
     */
    public function getCountRows(): int
    {
        return $this->repository->getCountRows();
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function edit(int $entityId, string $text): void
    {
        $entity = $this->repository->getById($entityId);
        $entity->edit($text);
        $this->repository->save($entity, $entityId);
    }

    /**
     * @inheritDoc
     */
    public function done(int $entityId): void
    {
        $entity = $this->repository->getById($entityId);
        $entity->done();
        $this->repository->save($entity, $entity->getId()->getValue());
    }
}
