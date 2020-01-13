<?php

namespace BeeJeeMVC\Tests\Lib\Repository;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Exceptions\CannotBeEmptyException;
use BeeJeeMVC\Lib\Exceptions\ForbiddenStatusException;
use BeeJeeMVC\Lib\Exceptions\NotAllowedEntityName;
use BeeJeeMVC\Lib\Exceptions\NotFoundException;
use BeeJeeMVC\Lib\Exceptions\NotValidEmailException;
use BeeJeeMVC\Lib\Exceptions\PdoErrorsException;
use BeeJeeMVC\Lib\Factory\Manager\EntityManagerFactory;
use BeeJeeMVC\Lib\Manager\EntityManagerInterface;
use BeeJeeMVC\Lib\Repository\EntityPdoRepository;
use PHPUnit\Framework\TestCase;

class EntityPdoRepositoryTest extends TestCase
{
    /**
     * @var App
     */
    protected $app;

    /**
     * @var EntityPdoRepository
     */
    protected $repository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var string 
     */
    protected $entityName;

    /**
     * @var string
     */
    protected $entityClassNamespace;

    /**
     * @throws NotAllowedEntityName
     */
    public function setUp()
    {
        $this->app = new App();
        $pdo = $this->app->getPdo();
        $this->entityName = $this->app->getEntityName();
        $this->entityClassNamespace = $this->app->getEntityClassNamespace();

        $this->repository = new EntityPdoRepository($pdo, $this->entityName, 3, $this->entityClassNamespace);

        $factory = new EntityManagerFactory($this->entityClassNamespace);
        $this->entityManager = $factory->create($this->entityName, $this->app->getRepository());
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotFoundException
     * @throws NotValidEmailException
     * @throws PdoErrorsException
     */
    public function shouldBeGettingEntityById(): void
    {
        $id = $this->entityManager->saveEntity('test_user_name_777', 'asdeqw@kljasd.com', 'asda sdkj iasd sad asd');

        $this->assertInstanceOf($this->entityClassNamespace.ucfirst(strtolower($this->entityName)), $this->repository->getEntityById($id));

        $this->entityManager->deleteEntity($id);
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     * @throws PdoErrorsException
     */
    public function shouldBeGettingCountEntities(): void
    {

        $id = $this->entityManager->saveEntity('test_user_name_777', 'asdeqw@kljasd.com', 'asdasd kjiasd sad asd');

        $this->assertLessThan($this->repository->getCountEntities(), 0);

        $this->entityManager->deleteEntity($id);
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     * @throws PdoErrorsException
     */
    public function shouldBeGettingAllEntities(): void
    {
        $id1 = $this->entityManager->saveEntity('test_user_name_777', 'asdeqw@kljasd.com', 'asdasd kji asd sad asd');
        $id2 = $this->entityManager->saveEntity('test_user_name_777', 'asdeqw@kljasd.com', 'asdasd kjia sd sad asd');
        $id3 = $this->entityManager->saveEntity('test_user_name_777', 'asdeqw@kljasd.com', 'asdasd kjias d sad asd');

        $this->assertCount(3, $this->repository->getEntities(1));

        $this->entityManager->deleteEntity($id1);
        $this->entityManager->deleteEntity($id2);
        $this->entityManager->deleteEntity($id3);
    }
}
