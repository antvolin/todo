<?php

namespace Todo\Lib\Factory\Entity;

use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Lib\Exceptions\NotValidEmailException;
use Todo\Model\Email;
use Todo\Model\EntityInterface;
use Todo\Model\Id;
use Todo\Model\Status;
use Todo\Model\Text;
use Todo\Model\UserName;

class EntityFactory implements EntityFactoryInterface
{
    private string $entityClass;

    public function __construct(string $entityClassNamespace, string $entityName)
    {
        $this->entityClass = $entityClassNamespace.ucfirst($entityName);
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
