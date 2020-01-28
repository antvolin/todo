<?php

namespace Tests\Lib\Factory\Paginator;

use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Lib\Exceptions\NotValidEmailException;
use Todo\Lib\Factory\Entity\EntityFactory;

class EntityFactoryTest extends TestCase
{
    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     */
    public function shouldBeCreatableEntityFactory(): void
    {
        $factory = new EntityFactory(App::getEntityClassNamespace(), App::getEntityName());

        $entity = [
            'id' => null,
            'user_name' => 'test_user',
            'email' => 'test@test.test',
            'text' => 'test text',
            'status' => null,
        ];

        $entity = $factory->create($entity);

        $this->assertTrue(method_exists($entity, 'getId'));
        $this->assertTrue(method_exists($entity, 'getUserName'));
        $this->assertTrue(method_exists($entity, 'getEmail'));
        $this->assertTrue(method_exists($entity, 'getText'));
        $this->assertTrue(method_exists($entity, 'getStatus'));
        $this->assertTrue(method_exists($entity, 'setStatus'));
        $this->assertTrue(method_exists($entity, 'setText'));
    }
}
