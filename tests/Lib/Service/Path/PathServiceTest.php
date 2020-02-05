<?php

namespace Tests\Lib\Service\Path;

use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
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
        $actual = PathService::separatePath('/part1/part2/part3/part4/');

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function shouldBeGettingCorrectPathToEntityStorage(): void
    {
        if (App::getRepositoryType() !== 'pdo') {
            $this->markTestSkipped();
        }

        $this->assertDirectoryExists(PathService::getPathToEntityStorage(App::getEntityName(), 3));
    }

    /**
     * @test
     */
    public function shouldBeGettingCorrectPathToTemplates(): void
    {
        $this->assertDirectoryExists(PathService::getPathToTemplates());
    }
}
