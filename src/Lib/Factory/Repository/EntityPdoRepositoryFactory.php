<?php

namespace Todo\Lib\Factory\Repository;

use PDO;
use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Repository\EntityPdoRepository;
use Todo\Lib\Repository\EntityRepositoryInterface;

class EntityPdoRepositoryFactory implements EntityRepositoryFactoryInterface
{
    private PDO $pdo;
    private EntityFactoryInterface $entityFactory;

    /**
     * @param PDO $pdo
     *
     * @param EntityFactoryInterface $entityFactory
     */
    public function __construct(Pdo $pdo, EntityFactoryInterface $entityFactory)
    {
        $this->pdo = $pdo;
        $this->entityFactory = $entityFactory;
    }

    /**
     * @inheritdoc
     */
    public function create(int $entityPerPage, string $entityName): EntityRepositoryInterface
    {
        return new EntityPdoRepository($this->pdo, $this->entityFactory, $entityPerPage, $entityName);
    }
}
