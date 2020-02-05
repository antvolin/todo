<?php

namespace Todo\Lib\Repository;

use Todo\Model\EntityInterface;
use Todo\Model\Id;

interface EntityRepositoryInterface
{
    public function getById(Id $entityId): EntityInterface;

    public function getCollection(int $page, ?string $orderBy = null, ?string $order = null): array;

    public function getCount(): int;

    public function add(EntityInterface $entity): Id;

    public function remove(Id $entityId): void;
}
