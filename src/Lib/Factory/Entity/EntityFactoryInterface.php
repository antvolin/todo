<?php

namespace Todo\Lib\Factory\Entity;

use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Lib\Exceptions\NotValidEmailException;
use Todo\Model\EntityInterface;

interface EntityFactoryInterface
{
    /**
     * @return string
     */
    public function getEntityName(): string;

    /**
     * @param array $entity
     *
     * @return EntityInterface
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     */
    public function create(array $entity): EntityInterface;
}
