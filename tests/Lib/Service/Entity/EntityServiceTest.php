<?php

namespace Tests\Lib\Service\Entity;

use Todo\Lib\App;
use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\CannotDoneEntityException;
use Todo\Lib\Exceptions\CannotEditEntityException;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Lib\Exceptions\NotAllowedEntityName;
use Todo\Lib\Exceptions\NotFoundException;
use Todo\Lib\Exceptions\PdoConnectionException as PdoConnectionExceptionAlias;
use Todo\Lib\Exceptions\PdoErrorsException;
use Todo\Lib\Exceptions\NotValidEmailException;
use Todo\Lib\Repository\EntityRepositoryInterface as EntityRepositoryInterfaceAlias;
use Todo\Lib\Service\Entity\EntityService;
use Todo\Lib\Service\Entity\EntityServiceInterface;
use Todo\Model\EntityInterface;
use Todo\Model\Status;
use PHPUnit\Framework\TestCase;

class EntityServiceTest extends TestCase
{
    /**
     * @var App
     */
    private $app;

    /**
     * @var EntityRepositoryInterfaceAlias
     */
    private $repository;

    /**
     * @var EntityServiceInterface
     */
    private $entityService;

    /**
     * @throws NotAllowedEntityName
     * @throws PdoConnectionExceptionAlias
     */
    protected function setUp()
    {
        $this->app = new App();
        $this->repository = $this->app->getRepository();
        $this->entityService = new EntityService(App::getEntityClassNamespace(), App::getEntityName());
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
        $id = $this->entityService->addEntity($this->repository, uniqid('user_name'.__METHOD__.__CLASS__, true), 'test@test.test', uniqid('text'.__METHOD__.__CLASS__, true));
        $entity = $this->entityService->getEntityById($this->repository, $id);

        $this->assertInstanceOf(EntityInterface::class, $entity);
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     * @throws PdoErrorsException
     */
    public function shouldBeGettingEntitiesArray(): void
    {
        $this->entityService->addEntity($this->repository, uniqid('user_name1'.__METHOD__.__CLASS__, true), 'test@test.test1', uniqid('text1'.__METHOD__.__CLASS__, true));
        $this->entityService->addEntity($this->repository, uniqid('user_name2'.__METHOD__.__CLASS__, true), 'test@test.test2', uniqid('text2'.__METHOD__.__CLASS__, true));
        $this->entityService->addEntity($this->repository, uniqid('user_name3'.__METHOD__.__CLASS__, true), 'test@test.test3', uniqid('text3'.__METHOD__.__CLASS__, true));

        $entities = $this->entityService->getEntities($this->repository, 0);

        foreach ($entities as $entity) {
            $this->assertInstanceOf(EntityInterface::class, $entity);
        }
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
    public function shouldBeGettingCountOfEntities(): void
    {
        $this->entityService->addEntity($this->repository, uniqid('user_name1'.__METHOD__.__CLASS__, true), 'test@test.test', uniqid('text1'.__METHOD__.__CLASS__, true));
        $this->entityService->addEntity($this->repository, uniqid('user_name2'.__METHOD__.__CLASS__, true), 'test@test.test', uniqid('text2'.__METHOD__.__CLASS__, true));
        $this->entityService->addEntity($this->repository, uniqid('user_name3'.__METHOD__.__CLASS__, true), 'test@test.test', uniqid('text3'.__METHOD__.__CLASS__, true));

        $count = $this->entityService->getCountEntities($this->app->getRepository());

        $this->assertLessThan($count, 0);
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
    public function shouldBeDeletingEntity(): void
    {
        $this->expectException(NotFoundException::class);

        $id = $this->entityService->addEntity($this->repository, uniqid('user_name'.__METHOD__.__CLASS__, true), 'test@test.test', uniqid('text'.__METHOD__.__CLASS__, true));

        $this->entityService->deleteEntity($this->repository, $id);
        $this->entityService->getEntityById($this->repository, $id);
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws CannotDoneEntityException
     * @throws CannotEditEntityException
     * @throws ForbiddenStatusException
     * @throws NotFoundException
     * @throws NotValidEmailException
     * @throws PdoErrorsException
     */
    public function shouldBeEditingEntity(): void
    {
        $newText = uniqid('text'.__METHOD__.__CLASS__, true);
        $id = $this->entityService->addEntity($this->repository, uniqid('user_name'.__METHOD__.__CLASS__, true), 'test@test.test', uniqid('text'.__METHOD__.__CLASS__, true));
        $this->entityService->editEntity($this->repository, $id, $newText);
        $entity = $this->entityService->getEntityById($this->repository, $id);

        $this->assertEquals(Status::EDITED, $entity->getStatus());
        $this->assertEquals($newText, $entity->getText());
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws CannotDoneEntityException
     * @throws ForbiddenStatusException
     * @throws NotFoundException
     * @throws NotValidEmailException
     * @throws PdoErrorsException
     */
    public function shouldBeDoneEntity(): void
    {
        $id = $this->entityService->addEntity($this->repository, uniqid('user_name'.__METHOD__.__CLASS__, true), 'test@test.test111', uniqid('text'.__METHOD__.__CLASS__, true));
        $this->entityService->doneEntity($this->repository, $id);
        $entity = $this->entityService->getEntityById($this->repository, $id);

        $this->assertEquals(Status::DONE, $entity->getStatus());
    }
}
