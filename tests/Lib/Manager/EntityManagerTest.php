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
        $id = $this->manager->save('test222', 'test@test.test', 'TEXT1');
        $entity = $this->manager->getById($id);
        $this->manager->delete($id);

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
        $id1 = $this->manager->save('test1', 'test@test.test1', 'TEXT1');
        $id2 = $this->manager->save('test2', 'test@test.test2', 'TEXT2');
        $id3 = $this->manager->save('test3', 'test@test.test3', 'TEXT3');

        $entities = $this->manager->getList(0);

        foreach ($entities as $entity) {
            $this->assertInstanceOf(EntityInterface::class, $entity);
        }

        $this->manager->delete($id1);
        $this->manager->delete($id2);
        $this->manager->delete($id3);
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
        $id1 = $this->manager->save('1_test_1_1', 'test@test.test', 'TEXT1');
        $id2 = $this->manager->save('2_test_2_2', 'test@test.test', 'TEXT2');
        $id3 = $this->manager->save('3_test_3_3', 'test@test.test', 'TEXT3');

        $count = $this->manager->getCountRows();

        $this->assertLessThan($count, 0);

        $this->manager->delete($id1);
        $this->manager->delete($id2);
        $this->manager->delete($id3);
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

        $id = $this->manager->save('1_test_1_1_1', 'test@test.test', 'TEXT1');

        $this->manager->delete($id);
        $this->manager->getById($id);
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
        $id = $this->manager->save('test111', 'test@test.test111', 'TEXT111');
        $this->manager->edit($id, 'TEXT2');
        $entity = $this->manager->getById($id);

        $this->assertEquals(Status::EDITED, $entity->getStatus());
        $this->assertEquals('TEXT2', $entity->getText());

        $this->manager->delete($id);
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
        $id = $this->manager->save('test111', 'test@test.test111', 'TEXT111');
        $this->manager->done($id);
        $entity = $this->manager->getById($id);

        $this->assertEquals(Status::DONE, $entity->getStatus());

        $this->manager->delete($id);
    }
}
