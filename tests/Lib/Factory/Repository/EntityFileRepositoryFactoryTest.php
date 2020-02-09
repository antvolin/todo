<?php

namespace Tests\Lib\Factory\Repository;

use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Exceptions\CannotCreateDirectoryException;
use Todo\Lib\Factory\Repository\EntityFileRepositoryFactory;
use Todo\Lib\Repository\EntityFileRepository;

class EntityFileRepositoryFactoryTest extends TestCase
{
    private int $entityPerPage;
    private string $entityName;

    protected function setUp()
    {
        $this->entityPerPage = 3;
        $this->entityName = App::getEntityName();
    }

    /**
     * @test
     *
     * @throws CannotCreateDirectoryException
     */
    public function shouldBeCreatableEntityFileRepository(): void
    {
        $factory = new EntityFileRepositoryFactory(
            $this->entityPerPage,
            $this->entityName
        );
        $repository = $factory->createRepository();

        $this->assertInstanceOf(EntityFileRepository::class, $repository);
    }
}
