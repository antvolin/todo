<?php

namespace Tests\Lib\Repository;

use Todo\Lib\App;
use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Lib\Exceptions\NotAllowedEntityName;
use Todo\Lib\Exceptions\EntityNotFoundException;
use Todo\Lib\Exceptions\NotValidEmailException;
use Todo\Lib\Exceptions\PdoConnectionException;
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
     * @var string
     */
    private $email;

    /**
     * @throws NotAllowedEntityName
     * @throws PdoConnectionException
     */
    public function setUp()
    {
        $this->email = 'test@test.test';
        $this->app = new App();
        $pdo = $this->app->getPdo();
        $this->entityName = App::getEntityName();
        $this->entityClassNamespace = App::getEntityClassNamespace();
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
     * @throws EntityNotFoundException
     * @throws NotValidEmailException
     * @throws PdoConnectionException
     * @throws PdoErrorsException
     */
    public function shouldBeGettingEntityById(): void
    {
        $userName = $this->generateUserName(__METHOD__, __CLASS__);
        $text = $this->generateText(__METHOD__, __CLASS__);

        $id = $this->entityService->addEntity($this->app->getRepository(), $userName, $this->email, $text);
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
     * @throws PdoConnectionException
     * @throws PdoErrorsException
     */
    public function shouldBeGettingCountEntities(): void
    {
        $userName = $this->generateUserName(__METHOD__, __CLASS__);
        $text = $this->generateText(__METHOD__, __CLASS__);

        $this->entityService->addEntity($this->app->getRepository(), $userName, $this->email, $text);

        $this->assertLessThan($this->repository->getCountEntities(), 0);
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotAllowedEntityName
     * @throws NotValidEmailException
     * @throws PdoConnectionException
     * @throws PdoErrorsException
     */
    public function shouldBeGettingAllEntities(): void
    {
        $userName1 = $this->generateUserName(__METHOD__, __CLASS__, 1);
        $userName2 = $this->generateUserName(__METHOD__, __CLASS__, 2);
        $userName3 = $this->generateUserName(__METHOD__, __CLASS__, 3);

        $text1 = $this->generateText(__METHOD__, __CLASS__, 1);
        $text2 = $this->generateText(__METHOD__, __CLASS__, 2);
        $text3 = $this->generateText(__METHOD__, __CLASS__, 3);

        $this->entityService->addEntity($this->app->getRepository(), $userName1, $this->email, $text1);
        $this->entityService->addEntity($this->app->getRepository(), $userName2, $this->email, $text2);
        $this->entityService->addEntity($this->app->getRepository(), $userName3, $this->email, $text3);

        $this->assertCount(3, $this->repository->getEntities(1));
    }

    /**
     * @param string $method
     * @param string $class
     * @param int $postfix
     *
     * @return string
     */
    private function generateUserName(string $method, string $class, int $postfix = 1): string
    {
        $fieldName = 'email';
        $fieldValue = sprintf('%s_%s_%s_%s', $fieldName, $method, $class, $postfix);

        return $this->generateField($fieldValue);
    }

    /**
     * @param string $method
     * @param string $class
     * @param int $postfix
     *
     * @return string
     */
    private function generateText(string $method, string $class, int $postfix = 1): string
    {
        $fieldName = 'text';
        $fieldValue = sprintf('%s_%s_%s_%s', $fieldName, $method, $class, $postfix);

        return $this->generateField($fieldValue);
    }

    /**
     * @param string $fieldValue
     *
     * @return string
     */
    private function generateField(string $fieldValue): string
    {
        return uniqid($fieldValue, true);
    }
}
