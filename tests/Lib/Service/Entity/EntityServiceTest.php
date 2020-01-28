<?php

namespace Tests\Lib\Service\Entity;

use Todo\Lib\App;
use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\CannotDoneEntityException;
use Todo\Lib\Exceptions\CannotEditEntityException;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Lib\Exceptions\NotAllowedEntityName;
use Todo\Lib\Exceptions\EntityNotFoundException;
use Todo\Lib\Exceptions\PdoConnectionException;
use Todo\Lib\Exceptions\PdoErrorsException;
use Todo\Lib\Exceptions\NotValidEmailException;
use Todo\Lib\Service\Entity\EntityService;
use Todo\Lib\Service\Entity\EntityServiceInterface;
use Todo\Lib\Traits\TestValueGenerator;
use Todo\Model\EntityInterface;
use Todo\Model\Status;
use PHPUnit\Framework\TestCase;

class EntityServiceTest extends TestCase
{
    use TestValueGenerator;

    /**
     * @var EntityServiceInterface
     */
    private $entityService;

    /**
     * @throws PdoConnectionException
     */
    protected function setUp()
    {
        $app = new App();
        $this->entityService = new EntityService(App::getEntityClassNamespace(), App::getEntityName());
        $this->entityService->setRepository($app->getRepository());
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws EntityNotFoundException
     * @throws NotValidEmailException
     * @throws PdoErrorsException
     */
    public function shouldBeGettingEntityById(): void
    {
        $userName = $this->generateUserName(__METHOD__, __CLASS__);
        $email = $this->generateEmail();
        $text = $this->generateText(__METHOD__, __CLASS__);

        $id = $this->entityService->addEntity($userName, $email, $text);
        $entity = $this->entityService->getEntityById($id);

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
        $userName1 = $this->generateUserName(__METHOD__, __CLASS__, 1);
        $userName2 = $this->generateUserName(__METHOD__, __CLASS__, 2);
        $userName3 = $this->generateUserName(__METHOD__, __CLASS__, 3);
        $text1 = $this->generateText(__METHOD__, __CLASS__, 1);
        $text2 = $this->generateText(__METHOD__, __CLASS__, 2);
        $text3 = $this->generateText(__METHOD__, __CLASS__, 3);
        $email = $this->generateEmail();

        $this->entityService->addEntity($userName1, $email, $text1);
        $this->entityService->addEntity($userName2, $email, $text2);
        $this->entityService->addEntity($userName3, $email, $text3);

        $entities = $this->entityService->getEntities(0);

        foreach ($entities as $entity) {
            $this->assertInstanceOf(EntityInterface::class, $entity);
        }
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
        $userName1 = $this->generateUserName(__METHOD__, __CLASS__, 1);
        $userName2 = $this->generateUserName(__METHOD__, __CLASS__, 2);
        $userName3 = $this->generateUserName(__METHOD__, __CLASS__, 3);
        $text1 = $this->generateText(__METHOD__, __CLASS__, 1);
        $text2 = $this->generateText(__METHOD__, __CLASS__, 2);
        $text3 = $this->generateText(__METHOD__, __CLASS__, 3);
        $email = $this->generateEmail();

        $this->entityService->addEntity($userName1, $email, $text1);
        $this->entityService->addEntity($userName2, $email, $text2);
        $this->entityService->addEntity($userName3, $email, $text3);

        $count = $this->entityService->getCountEntities();

        $this->assertLessThan($count, 0);
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws EntityNotFoundException
     * @throws NotValidEmailException
     * @throws PdoErrorsException
     */
    public function shouldBeDeletingEntity(): void
    {
        $this->expectException(EntityNotFoundException::class);

        $userName = $this->generateUserName(__METHOD__, __CLASS__);
        $text = $this->generateText(__METHOD__, __CLASS__);
        $email = $this->generateEmail();

        $id = $this->entityService->addEntity($userName, $email, $text);

        $this->entityService->deleteEntity($id);
        $this->entityService->getEntityById($id);
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws CannotDoneEntityException
     * @throws CannotEditEntityException
     * @throws ForbiddenStatusException
     * @throws EntityNotFoundException
     * @throws NotValidEmailException
     * @throws PdoErrorsException
     */
    public function shouldBeEditingEntity(): void
    {
        $newText = $this->generateText(__METHOD__, __CLASS__, 1);

        $userName = $this->generateUserName(__METHOD__, __CLASS__);
        $text = $this->generateText(__METHOD__, __CLASS__);
        $email = $this->generateEmail();

        $id = $this->entityService->addEntity($userName, $email, $text);

        $this->entityService->editEntity($id, $newText);
        $entity = $this->entityService->getEntityById($id);

        $this->assertEquals(Status::EDITED, $entity->getStatus());
        $this->assertEquals($newText, $entity->getText());
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws CannotDoneEntityException
     * @throws ForbiddenStatusException
     * @throws EntityNotFoundException
     * @throws NotValidEmailException
     * @throws PdoErrorsException
     */
    public function shouldBeDoneEntity(): void
    {
        $userName = $this->generateUserName(__METHOD__, __CLASS__);
        $text = $this->generateText(__METHOD__, __CLASS__);
        $email = $this->generateEmail();

        $id = $this->entityService->addEntity($userName, $email, $text);
        $this->entityService->doneEntity($id);
        $entity = $this->entityService->getEntityById($id);

        $this->assertEquals(Status::DONE, $entity->getStatus());
    }
}
