<?php

namespace Todo\Lib\Repository;

use Redis;
use Todo\Lib\Exceptions\EntityNotFoundException;
use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Model\EntityInterface;
use Todo\Model\Id;

class EntityRedisRepository implements EntityRepositoryInterface
{
    private Redis $redis;
    private EntityFactoryInterface $entityFactory;
    private int $entityPerPage;

    public function __construct(
        Redis $redis,
        EntityFactoryInterface $entityFactory,
        int $entityPerPage
    )
    {
        $this->redis = $redis;
        $this->entityFactory = $entityFactory;
        $this->entityPerPage = $entityPerPage;
    }

    /**
     * @param Id $entityId
     *
     * @return EntityInterface
     *
     * @throws EntityNotFoundException
     */
    public function getById(Id $entityId): EntityInterface
    {
        $entity = $this->redis->get($entityId->getValue());

        if (!$entity) {
            throw new EntityNotFoundException();
        }

        $entity = array_merge(unserialize($entity), ['id' => $entityId->getValue()]);

        return $this->entityFactory->create($entity);
    }

    /**
     * @param int $page
     *
     * @return array
     *
     * @throws EntityNotFoundException
     */
    public function getCollection(int $page): array
    {
        $result = [];
        $id = $this->generateIdByPage($page);

        for ($i = 0; $i < $this->entityPerPage; ++$i) {
            $result[] = $this->getById($this->generateId($id + $i));
        }

        return $result;
    }

    public function getCount(): int
    {
        return count($this->redis->keys('id_*'));
    }

    public function add(EntityInterface $entity): Id
    {
        $entityId = $entity->getId();

        if (!$entityId->getValue()) {
            $entityId = $this->generateId();
        }

        $data = [
            'user_name' => $entity->getUserName(),
            'email' => $entity->getEmail(),
            'text' => $entity->getText(),
            'status' => $entity->getStatus(),
        ];

        $this->redis->set($entityId->getValue(), serialize($data));

        return $entityId;
    }

    public function remove(Id $entityId): void
    {
        $this->redis->del($entityId->getValue());
    }

    private function generateId(int $count = null): Id
    {
        if (!$count) {
            $count = $this->getCount();
        }

        return new Id('id_'.++$count);
    }

    private function generateIdByPage(int $page): int
    {
        return (($page - 1) * $this->entityPerPage) + 1;
    }
}
