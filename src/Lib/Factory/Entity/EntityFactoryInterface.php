<?php

namespace Todo\Lib\Factory\Entity;

use Todo\Model\EntityInterface;

interface EntityFactoryInterface
{
    public function create(array $entity): EntityInterface;
}
