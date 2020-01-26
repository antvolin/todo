<?php

namespace Todo\Lib\Factory\Repository;

use Todo\Lib\Exceptions\NotAllowedEntityName;
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
    private $entityService;

    /**
     * @param PDO $pdo
     * @param EntityServiceInterface $entityService
     *
     * @throws NotAllowedEntityName
     */
    public function __construct(Pdo $pdo, EntityServiceInterface $entityService)
    {
        parent::__construct($entityService->getEntityName());

        $this->pdo = $pdo;
        $this->entityService = $entityService;
    }

    /**
     * @inheritdoc
     */
    public function create(int $entityPerPage): EntityRepositoryInterface
    {
        return new EntityPdoRepository($this->pdo, $this->entityService, $entityPerPage);
    }
}
