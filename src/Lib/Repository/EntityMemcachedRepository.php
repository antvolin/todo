<?php

namespace Todo\Lib\Repository;

use Memcached;
use Todo\Lib\Exceptions\EntityNotFoundException;
use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Model\EntityInterface;
use Todo\Model\Id;

class EntityMemcachedRepository implements EntityRepositoryInterface
{
    private Memcached $memcached;
    private EntityFactoryInterface $entityFactory;
    private int $entityPerPage;
    private string $server;

    public function __construct(
        Memcached $memcached,
        EntityFactoryInterface $entityFactory,
        int $entityPerPage,
        string $server
    )
    {
        $this->memcached = $memcached;
        $this->entityFactory = $entityFactory;
        $this->entityPerPage = $entityPerPage;
        $this->server = $server;
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
        $entity = $this->memcached->get($entityId->getValue());

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
        return $this->memcached->getStats()[$this->server]['total_items'];
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

        $this->memcached->set($entityId->getValue(), serialize($data));

        return $entityId;
    }

    public function remove(Id $entityId): void
    {
        $this->memcached->delete($entityId->getValue());
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
