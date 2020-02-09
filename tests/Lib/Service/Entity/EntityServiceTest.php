<?php

namespace Tests\Lib\Service\Entity;

use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\CannotCreateDirectoryException;
use Todo\Lib\Exceptions\CannotDoneEntityException;
use Todo\Lib\Exceptions\CannotEditEntityException;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Lib\Exceptions\EntityNotFoundException;
use Todo\Lib\Exceptions\PdoConnectionException;
use Todo\Lib\Exceptions\RedisConnectionException;
use Todo\Lib\Service\Entity\EntityService;
use Todo\Lib\Service\Entity\EntityServiceInterface;
use Todo\Lib\Traits\TestValueGenerator;
use Todo\Model\EntityInterface;
use Todo\Model\Status;

class EntityServiceTest extends TestCase
{
    use TestValueGenerator;

    private EntityServiceInterface $entityService;

    /**
     * @throws CannotCreateDirectoryException
     * @throws PdoConnectionException
     * @throws RedisConnectionException
     */
    protected function setUp()
    {
        $app = new App();
        $this->entityService = new EntityService($app->createEntityFactory());
        $this->entityService->setRepository($app->createRepository());
    }

    /**
     * @test
     */
    public function shouldBeGettingEntityById(): void
    {
        $userName = $this->generateUserName(__METHOD__, __CLASS__);
        $email = $this->generateEmail();
        $text = $this->generateText(__METHOD__, __CLASS__);

        $id = $this->entityService->add($userName, $email, $text);
        $entity = $this->entityService->getById($id);

        $this->assertInstanceOf(EntityInterface::class, $entity);
    }

    /**
     * @test
     */
    public function shouldBeGettingEntitiesArray(): void
    {
        $userName1 = $this->generateUserName(__METHOD__, __CLASS__, 1);
        $userName2 = $this->generateUserName(__METHOD__, __CLASS__, 2);
        $userName3 = $this->generateUserName(__METHOD__, __CLASS__, 3);
        $text1 = $this->generateText(__METHOD__, __CLASS__, 1);
        $text2 = $this->generateText(__METHOD__, __CLASS__, 2);
        $text3 = $this->generateText(__METHOD__, __CLASS__, 3);
        $email = $this->generateEmail();

        $this->entityService->add($userName1, $email, $text1);
        $this->entityService->add($userName2, $email, $text2);
        $this->entityService->add($userName3, $email, $text3);

        $entities = $this->entityService->getCollection(1);

        foreach ($entities as $entity) {
            $this->assertInstanceOf(EntityInterface::class, $entity);
        }
    }

    /**
     * @test
     */
    public function shouldBeGettingCountOfEntities(): void
    {
        $userName1 = $this->generateUserName(__METHOD__, __CLASS__, 1);
        $userName2 = $this->generateUserName(__METHOD__, __CLASS__, 2);
        $userName3 = $this->generateUserName(__METHOD__, __CLASS__, 3);
        $text1 = $this->generateText(__METHOD__, __CLASS__, 1);
        $text2 = $this->generateText(__METHOD__, __CLASS__, 2);
        $text3 = $this->generateText(__METHOD__, __CLASS__, 3);
        $email = $this->generateEmail();

        $this->entityService->add($userName1, $email, $text1);
        $this->entityService->add($userName2, $email, $text2);
        $this->entityService->add($userName3, $email, $text3);

        $count = $this->entityService->getCount();

        $this->assertLessThan($count, 0);
    }

    /**
     * @test
     */
    public function shouldBeDeletingEntity(): void
    {
        $this->expectException(EntityNotFoundException::class);

        $userName = $this->generateUserName(__METHOD__, __CLASS__);
        $text = $this->generateText(__METHOD__, __CLASS__);
        $email = $this->generateEmail();

        $id = $this->entityService->add($userName, $email, $text);

        $this->entityService->remove($id);
        $this->entityService->getById($id);
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws CannotDoneEntityException
     * @throws CannotEditEntityException
     * @throws ForbiddenStatusException
     */
    public function shouldBeEditingEntity(): void
    {
        $newText = $this->generateText(__METHOD__, __CLASS__, 1);
        $userName = $this->generateUserName(__METHOD__, __CLASS__);
        $text = $this->generateText(__METHOD__, __CLASS__);
        $email = $this->generateEmail();

        $id = $this->entityService->add($userName, $email, $text);
        $this->entityService->edit($id, $newText);
        $entity = $this->entityService->getById($id);

        $this->assertEquals(Status::EDITED, $entity->getStatus());
        $this->assertEquals($newText, $entity->getText());
    }

    /**
     * @test
     *
     * @throws CannotDoneEntityException
     * @throws ForbiddenStatusException
     */
    public function shouldBeDoneEntity(): void
    {
        $userName = $this->generateUserName(__METHOD__, __CLASS__);
        $text = $this->generateText(__METHOD__, __CLASS__);
        $email = $this->generateEmail();

        $id = $this->entityService->add($userName, $email, $text);
        $this->entityService->done($id);
        $entity = $this->entityService->getById($id);

        $this->assertEquals(Status::DONE, $entity->getStatus());
    }
}
