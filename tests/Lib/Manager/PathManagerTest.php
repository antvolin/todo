<?php

namespace BeeJeeMVC\Tests\Lib\Manager;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Exceptions\CannotBeEmptyException;
use BeeJeeMVC\Lib\Exceptions\ForbiddenStatusException;
use BeeJeeMVC\Lib\Exceptions\NotAllowedEntityName;
use BeeJeeMVC\Lib\Exceptions\NotValidEmailException;
use BeeJeeMVC\Lib\Exceptions\PdoErrorsException;
use BeeJeeMVC\Lib\Manager\PathManager;
use PDO;
use PHPUnit\Framework\TestCase;

class PathManagerTest extends TestCase
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
        $actual = PathManager::getPathParts('/part1/part2/part3/part4/');

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function shouldBeGettingCorrectPathToSource(): void
    {
        $this->assertDirectoryExists(PathManager::getSrcPathByLevel(2));
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
        $entityName = $_ENV['ENTITY_NAME'];
        $dsn = PathManager::getPathToPdoDsn($_ENV['STORAGE_TYPE'], $_ENV['DB_FOLDER_NAME'], $entityName);
        $pdo = new PDO($dsn);

        $entityManager = (new App())->getEntityManager();
        $id = $entityManager->save('test_user_ Name', 'testEmail@testEmail.testEmail', 'lkasjd lask asd dj ali');

        $count = $pdo->query('SELECT count(id) FROM '.$entityName)->fetchColumn();

        $this->assertLessThan($count, 0);

        $entityManager->delete($id);
    }

    /**
     * @test
     */
    public function shouldBeGettingCorrectPathToTemplates(): void
    {
        $this->assertDirectoryExists(PathManager::getPathToTemplates());
    }
}
