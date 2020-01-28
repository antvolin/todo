<?php

namespace Todo\Lib\Factory\Repository;

use PDO;
use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Repository\EntityPdoRepository;
use Todo\Lib\Repository\EntityRepositoryInterface;
use Todo\Lib\Service\Entity\EntityServiceInterface;

class EntityPdoRepositoryFactory extends EntityRepositoryFactory
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var EntityServiceInterface
     */
    private $entityFactory;

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
