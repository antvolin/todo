<?php

namespace Tests\Lib\Service\Path;

use Todo\Lib\App;
use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Lib\Exceptions\NotAllowedEntityName;
use Todo\Lib\Exceptions\NotValidEmailException;
use Todo\Lib\Exceptions\PdoErrorsException;
use PDO;
use PHPUnit\Framework\TestCase;
use Todo\Lib\Service\Path\PathService;

class PathServiceTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeGettingPathParts(): void
    {
        $expected = [
            'part1',
            'part2',
            'part3',
            'part4',
        ];
        $actual = PathService::getPathParts('/part1/part2/part3/part4/');

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function shouldBeGettingCorrectPathToSource(): void
    {
        $this->assertDirectoryExists(PathService::getSrcPathByLevel(2));
    }

    /**
     * @test
     *
     * @throws NotAllowedEntityName
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     * @throws PdoErrorsException
     */
    public function shouldBeGettingCorrectPathToPdoDsn(): void
    {
        $app = new App();
        $entityName = $app->getEntityName();
        $dsn = PathService::getPathToPdoDsn($app->getStorageType(), $app->getDbFolderName(), $entityName);
        $pdo = new PDO($dsn);

        $entityManager = $app->getEntityManager();
        $entityManager->saveEntity(uniqid('user_name'.__METHOD__.__CLASS__, true), 'test@test.test', uniqid('text'.__METHOD__.__CLASS__, true));

        $count = $pdo->query('SELECT count(id) FROM '.$entityName)->fetchColumn();

        $this->assertLessThan($count, 0);
    }

    /**
     * @test
     */
    public function shouldBeGettingCorrectPathToTemplates(): void
    {
        $this->assertDirectoryExists(PathService::getPathToTemplates());
    }
}
