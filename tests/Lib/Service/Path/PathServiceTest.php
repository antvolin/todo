<?php

namespace Tests\Lib\Service\Path;

use PDO;
use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Lib\Exceptions\NotValidEmailException;
use Todo\Lib\Exceptions\PdoConnectionException;
use Todo\Lib\Exceptions\PdoErrorsException;
use Todo\Lib\Service\Path\PathService;
use Todo\Lib\Traits\TestValueGenerator;

class PathServiceTest extends TestCase
{
    use TestValueGenerator;

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
        $this->markTestIncomplete();

//        $this->assertDirectoryExists(PathService::getEntityStoragePath(2));
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     * @throws PdoErrorsException
     * @throws PdoConnectionException
     */
    public function shouldBeGettingCorrectPathToPdoDsn(): void
    {
        $this->markTestSkipped();

        $app = new App();
        $entityName = App::getEntityName();
        $dsn = PathService::getPathToPdoDsn(App::getStorageType(), App::getDbFolderName(), $entityName);
        $pdo = new PDO($dsn);

        $entityService = $app->getEntityService();
        $entityService->setRepository($app->getRepository());

        $userName = $this->generateUserName(__METHOD__, __CLASS__);
        $text = $this->generateText(__METHOD__, __CLASS__);
        $email = $this->generateEmail();
        $entityService->addEntity($userName, $email, $text);

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
