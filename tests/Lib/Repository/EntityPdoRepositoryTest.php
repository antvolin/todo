<?php

namespace Tests\Lib\Repository;

use Todo\Lib\App;
use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Lib\Exceptions\NotAllowedEntityName;
use Todo\Lib\Exceptions\NotFoundException;
use Todo\Lib\Exceptions\NotValidEmailException;
use Todo\Lib\Exceptions\PdoConnectionException as PdoConnectionExceptionAlias;
use Todo\Lib\Exceptions\PdoErrorsException;
use Todo\Lib\Factory\Service\EntityServiceFactory;
use Todo\Lib\Repository\EntityPdoRepository;
use PHPUnit\Framework\TestCase;
use Todo\Lib\Service\Entity\EntityServiceInterface;

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
    protected $entityService;

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
     * @throws PdoConnectionExceptionAlias
     */
    public function setUp()
    {
        $this->app = new App();
        $pdo = $this->app->getPdo();
        $this->entityName = $this->app->getEntityName();
        $this->entityClassNamespace = $this->app->getEntityClassNamespace();
        $this->repository = new EntityPdoRepository($pdo, $this->app->getEntityService(), 3);
        $factory = new EntityServiceFactory($this->entityClassNamespace);
        $this->entityService = $factory->create($this->entityName);
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotAllowedEntityName
     * @throws NotFoundException
     * @throws NotValidEmailException
     * @throws PdoConnectionExceptionAlias
     * @throws PdoErrorsException
     */
    public function shouldBeGettingEntityById(): void
    {
        $id = $this->entityService->saveEntity($this->app->getRepository(), uniqid('user_name'.__METHOD__.__CLASS__, true), 'test@test.test', uniqid('text'.__METHOD__.__CLASS__, true));
        $entity = $this->repository->getEntityById($id);

        $this->assertTrue(method_exists($entity, 'getStatus'));
        $this->assertTrue(method_exists($entity, 'getText'));
        $this->assertTrue(method_exists($entity, 'getEmail'));
        $this->assertTrue(method_exists($entity, 'getId'));
        $this->assertTrue(method_exists($entity, 'getUserName'));
        $this->assertTrue(method_exists($entity, 'setStatus'));
        $this->assertTrue(method_exists($entity, 'setText'));
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotAllowedEntityName
     * @throws NotValidEmailException
     * @throws PdoConnectionExceptionAlias
     * @throws PdoErrorsException
     */
    public function shouldBeGettingCountEntities(): void
    {
        $this->entityService->saveEntity($this->app->getRepository(), uniqid('user_name'.__METHOD__.__CLASS__, true), 'test@test.test', uniqid('text'.__METHOD__.__CLASS__, true));

        $this->assertLessThan($this->repository->getCountEntities(), 0);
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotAllowedEntityName
     * @throws NotValidEmailException
     * @throws PdoConnectionExceptionAlias
     * @throws PdoErrorsException
     */
    public function shouldBeGettingAllEntities(): void
    {
        $this->entityService->saveEntity($this->app->getRepository(), uniqid('user_name1'.__METHOD__.__CLASS__, true), 'test@test.test', uniqid('text1'.__METHOD__.__CLASS__, true));
        $this->entityService->saveEntity($this->app->getRepository(), uniqid('user_name2'.__METHOD__.__CLASS__, true), 'test@test.test', uniqid('text2'.__METHOD__.__CLASS__, true));
        $this->entityService->saveEntity($this->app->getRepository(), uniqid('user_name3'.__METHOD__.__CLASS__, true), 'test@test.test', uniqid('text3'.__METHOD__.__CLASS__, true));

        $this->assertCount(3, $this->repository->getEntities(1));
    }
}
