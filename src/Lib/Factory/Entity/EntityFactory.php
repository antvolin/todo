<?php

namespace Todo\Lib\Factory\Entity;

use Todo\Model\Email;
use Todo\Model\EntityInterface;
use Todo\Model\Id;
use Todo\Model\Status;
use Todo\Model\Text;
use Todo\Model\UserName;

class EntityFactory implements EntityFactoryInterface
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
     * @param string $entityClassNamespace
     * @param string $entityName
     */
    public function __construct(string $entityClassNamespace, string $entityName)
    {
        $this->entityClass = $entityClassNamespace.ucfirst($entityName);
        $this->entityName = $entityName;
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
    public function create(array $entity): EntityInterface
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
}
