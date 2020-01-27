<?php

namespace Todo\Lib\Factory\Repository;

use Todo\Lib\Exceptions\NotAllowedEntityName;
use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Repository\EntityPdoRepository;
use Todo\Lib\Repository\EntityRepositoryInterface;
use Todo\Lib\Service\Entity\EntityServiceInterface;
use PDO;

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
     *
     * @throws NotAllowedEntityName
     */
    public function __construct(Pdo $pdo, EntityFactoryInterface $entityFactory)
    {
        parent::__construct($entityFactory->getEntityName());

        $this->pdo = $pdo;
        $this->entityFactory = $entityFactory;
    }

    /**
     * @inheritdoc
     */
    public function create(int $entityPerPage): EntityRepositoryInterface
    {
        return new EntityPdoRepository($this->pdo, $this->entityFactory, $entityPerPage);
    }
}
