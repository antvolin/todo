<?php

namespace Todo\Lib\Service\Entity;

use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\CannotDoneEntityException;
use Todo\Lib\Exceptions\CannotEditEntityException;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Lib\Exceptions\NotValidEmailException;
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
    public function getEntityById(EntityRepositoryInterface $repository, int $id): EntityInterface
    {
        return $repository->getEntityById($id);
    }

    /**
     * @inheritDoc
     */
    public function getEntities(EntityRepositoryInterface $repository, int $page, ?string $orderBy = null, ?string $order = null): array
    {
        return $repository->getEntities($page, $orderBy, $order);
    }

    /**
     * @inheritDoc
     */
    public function getCountEntities(EntityRepositoryInterface $repository): int
    {
        return $repository->getCountEntities();
    }

    /**
     * @inheritDoc
     */
    public function saveEntity(EntityRepositoryInterface $repository, string $userName, string $email, string $text): int
    {
        return $repository->saveEntity(
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
     * @param array $entity
     *
     * @return EntityInterface
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
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
    public function deleteEntity(EntityRepositoryInterface $repository, int $entityId): void
    {
        $repository->deleteEntity($entityId);
    }

    /**
     * @inheritDoc
     */
    public function editEntity(EntityRepositoryInterface $repository, int $entityId, string $text): void
    {
        $entity = $repository->getEntityById($entityId);

        if (Status::DONE == $entity->getStatus()) {
            throw new CannotEditEntityException();
        }

        $entity->setStatus(new Status(Status::EDITED));
        $entity->setText(new Text($text));

        $repository->saveEntity($entity, $entityId);
    }

    /**
     * @inheritDoc
     */
    public function doneEntity(EntityRepositoryInterface $repository, int $entityId): void
    {
        $entity = $repository->getEntityById($entityId);

        if (Status::DONE == $entity->getStatus()) {
            throw new CannotDoneEntityException();
        }

        $entity->setStatus(new Status(Status::DONE));

        $repository->saveEntity($entity, $entity->getId()->getValue());
    }
}
