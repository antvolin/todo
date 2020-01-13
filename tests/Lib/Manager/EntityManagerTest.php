<?php

namespace BeeJeeMVC\Tests\Lib\Manager;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Exceptions\CannotBeEmptyException;
use BeeJeeMVC\Lib\Exceptions\CannotDoneEntityException;
use BeeJeeMVC\Lib\Exceptions\CannotEditEntityException;
use BeeJeeMVC\Lib\Exceptions\ForbiddenStatusException;
use BeeJeeMVC\Lib\Exceptions\NotAllowedEntityName;
use BeeJeeMVC\Lib\Exceptions\NotFoundException;
use BeeJeeMVC\Lib\Exceptions\PdoErrorsException;
use BeeJeeMVC\Lib\Exceptions\NotValidEmailException;
use BeeJeeMVC\Lib\Manager\EntityManager;
use BeeJeeMVC\Lib\Manager\EntityManagerInterface;
use BeeJeeMVC\Model\EntityInterface;
use BeeJeeMVC\Model\Status;
use PHPUnit\Framework\TestCase;

class EntityManagerTest extends TestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @throws NotAllowedEntityName
     */
    protected function setUp()
    {
        $repository = (new App())->getRepository();
        $entityClass = $_ENV['ENTITY_CLASS_NAMESPACE'].ucfirst(strtolower($_ENV['ENTITY_NAME']));
        $this->manager = new EntityManager($entityClass, $repository);
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
        $id = $this->manager->saveEntity('test222', 'test@test.test', 'TEXT12');
        $entity = $this->manager->getEntityById($id);
        $this->manager->deleteEntity($id);

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
        $id1 = $this->manager->saveEntity('test1', 'test@test.test1', 'TEXT13');
        $id2 = $this->manager->saveEntity('test2', 'test@test.test2', 'TEXT24');
        $id3 = $this->manager->saveEntity('test3', 'test@test.test3', 'TEXT35');

        $entities = $this->manager->getEntities(0);

        foreach ($entities as $entity) {
            $this->assertInstanceOf(EntityInterface::class, $entity);
        }

        $this->manager->deleteEntity($id1);
        $this->manager->deleteEntity($id2);
        $this->manager->deleteEntity($id3);
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     * @throws PdoErrorsException
     */
    public function shouldBeGettingCountOfEntities(): void
    {
        $id1 = $this->manager->saveEntity('1_test_1_1', 'test@test.test', 'TEXT1');
        $id2 = $this->manager->saveEntity('2_test_2_2', 'test@test.test', 'TEXT2');
        $id3 = $this->manager->saveEntity('3_test_3_3', 'test@test.test', 'TEXT3');

        $count = $this->manager->getCountEntities();

        $this->assertLessThan($count, 0);

        $this->manager->deleteEntity($id1);
        $this->manager->deleteEntity($id2);
        $this->manager->deleteEntity($id3);
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     * @throws PdoErrorsException
     * @throws NotFoundException
     */
    public function shouldBeDeletingEntity(): void
    {
        $this->expectException(NotFoundException::class);

        $id = $this->manager->saveEntity('1_test_1_1_1', 'test@test.test', 'TEXT1');

        $this->manager->deleteEntity($id);
        $this->manager->getEntityById($id);
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     * @throws PdoErrorsException
     * @throws NotFoundException
     * @throws CannotEditEntityException
     */
    public function shouldBeEditingEntity(): void
    {
        $id = $this->manager->saveEntity('test111', 'test@test.test111', 'TEXT1asd11');
        $this->manager->editEntity($id, 'TEXT2');
        $entity = $this->manager->getEntityById($id);

        $this->assertEquals(Status::EDITED, $entity->getStatus());
        $this->assertEquals('TEXT2', $entity->getText());

        $this->manager->deleteEntity($id);
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     * @throws PdoErrorsException
     * @throws NotFoundException
     * @throws CannotDoneEntityException
     */
    public function shouldBeDoneEntity(): void
    {
        $id = $this->manager->saveEntity('test111', 'test@test.test111', 'TEXT11dlsak1');
        $this->manager->doneEntity($id);
        $entity = $this->manager->getEntityById($id);

        $this->assertEquals(Status::DONE, $entity->getStatus());

        $this->manager->deleteEntity($id);
    }
}
