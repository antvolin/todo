<?php

namespace Tests\Lib\Repository;

use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Lib\Exceptions\EntityNotFoundException;
use Todo\Lib\Exceptions\NotValidEmailException;
use Todo\Lib\Exceptions\PdoConnectionException;
use Todo\Lib\Exceptions\PdoErrorsException;
use Todo\Lib\Repository\EntityRepositoryInterface;
use Todo\Lib\Service\Entity\EntityServiceInterface;
use Todo\Lib\Traits\TestValueGenerator;

class EntityRepositoryTest extends TestCase
{
    use TestValueGenerator;

    private EntityRepositoryInterface $repository;
    private EntityServiceInterface $entityService;

    /**
     * @throws PdoConnectionException
     */
    protected function setUp()
    {
        $app = new App();
        $this->repository = $app->getRepository();
        $this->entityService = $app->getEntityService();
        $this->entityService->setRepository($this->repository);
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws EntityNotFoundException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     * @throws PdoErrorsException
     */
    public function shouldBeGettingEntityById(): void
    {
        $userName = $this->generateUserName(__METHOD__, __CLASS__);
        $text = $this->generateText(__METHOD__, __CLASS__);

        $id = $this->entityService->add($userName, $this->generateEmail(), $text);
        $entity = $this->repository->getById($id);

        $this->assertTrue(method_exists($entity, 'getId'));
        $this->assertTrue(method_exists($entity, 'getUserName'));
        $this->assertTrue(method_exists($entity, 'getEmail'));
        $this->assertTrue(method_exists($entity, 'getText'));
        $this->assertTrue(method_exists($entity, 'getStatus'));
        $this->assertTrue(method_exists($entity, 'setText'));
        $this->assertTrue(method_exists($entity, 'setStatus'));
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
        $userName = $this->generateUserName(__METHOD__, __CLASS__);
        $text = $this->generateText(__METHOD__, __CLASS__);

        $this->entityService->add($userName, $this->generateEmail(), $text);

        $this->assertLessThan($this->repository->getCount(), 0);
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     * @throws PdoErrorsException
     */
    public function shouldBeGettingCollectionOfEntities(): void
    {
        $userName1 = $this->generateUserName(__METHOD__, __CLASS__, 1);
        $userName2 = $this->generateUserName(__METHOD__, __CLASS__, 2);
        $userName3 = $this->generateUserName(__METHOD__, __CLASS__, 3);

        $text1 = $this->generateText(__METHOD__, __CLASS__, 1);
        $text2 = $this->generateText(__METHOD__, __CLASS__, 2);
        $text3 = $this->generateText(__METHOD__, __CLASS__, 3);

        $this->entityService->add($userName1, $this->generateEmail(), $text1);
        $this->entityService->add($userName2, $this->generateEmail(), $text2);
        $this->entityService->add($userName3, $this->generateEmail(), $text3);

        $this->assertCount(3, $this->repository->getCollection(1));
    }
}
