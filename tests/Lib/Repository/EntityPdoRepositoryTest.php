<?php

namespace BeeJeeMVC\Tests\Lib\Repository;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Exceptions\CannotBeEmptyException;
use BeeJeeMVC\Lib\Exceptions\ForbiddenStatusException;
use BeeJeeMVC\Lib\Exceptions\NotAllowedEntityName;
use BeeJeeMVC\Lib\Exceptions\NotFoundException;
use BeeJeeMVC\Lib\Exceptions\NotValidEmailException;
use BeeJeeMVC\Lib\Exceptions\PdoErrorsException;
use BeeJeeMVC\Lib\Factory\Service\EntityServiceFactory;
use BeeJeeMVC\Lib\Service\EntityServiceInterface;
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
     * @var EntityServiceInterface
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
        $factory = new EntityServiceFactory($this->entityClassNamespace);
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
        $id = $this->entityManager->saveEntity(uniqid('user_name'.__METHOD__.__CLASS__, true), 'test@test.test', uniqid('text'.__METHOD__.__CLASS__, true));
        $entity = $this->repository->getEntityById($id);

        $this->assertTrue(method_exists($entity, 'getStatus'));
        $this->assertTrue(method_exists($entity, 'done'));
        $this->assertTrue(method_exists($entity, 'getText'));
        $this->assertTrue(method_exists($entity, 'edit'));
        $this->assertTrue(method_exists($entity, 'getEmail'));
        $this->assertTrue(method_exists($entity, 'getId'));
        $this->assertTrue(method_exists($entity, 'setStatus'));
        $this->assertTrue(method_exists($entity, 'getUserName'));
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
        $this->entityManager->saveEntity(uniqid('user_name'.__METHOD__.__CLASS__, true), 'test@test.test', uniqid('text'.__METHOD__.__CLASS__, true));

        $this->assertLessThan($this->repository->getCountEntities(), 0);
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
        $this->entityManager->saveEntity(uniqid('user_name1'.__METHOD__.__CLASS__, true), 'test@test.test', uniqid('text1'.__METHOD__.__CLASS__, true));
        $this->entityManager->saveEntity(uniqid('user_name2'.__METHOD__.__CLASS__, true), 'test@test.test', uniqid('text2'.__METHOD__.__CLASS__, true));
        $this->entityManager->saveEntity(uniqid('user_name3'.__METHOD__.__CLASS__, true), 'test@test.test', uniqid('text3'.__METHOD__.__CLASS__, true));

        $this->assertCount(3, $this->repository->getEntities(1));
    }
}
