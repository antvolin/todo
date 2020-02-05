<?php

namespace Todo\Lib\Service\Entity;

use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Repository\EntityRepositoryInterface;
use Todo\Model\EntityInterface;
use Todo\Model\Id;

interface EntityServiceInterface
{
    public function __construct(EntityFactoryInterface $factory);

    public function getById(Id $id): EntityInterface;

    public function getCollection(int $page, ?string $orderBy = null, ?string $order = null): array;

    public function getCount(): int;

    public function setRepository(EntityRepositoryInterface $repository): void;

    public function edit(Id $entityId, string $text): void;

    public function done(Id $entityId): void;

    public function add(string $userName, string $email, string $text): Id;

    public function remove(Id $entityId): void;
}
