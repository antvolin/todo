<?php

namespace Todo\Lib\Repository;

use FilesystemIterator;
use Todo\Lib\Exceptions\EntityNotFoundException;
use Todo\Lib\Service\Ordering\OrderingService;
use Todo\Model\EntityInterface;
use Todo\Model\Id;

class EntityFileRepository implements EntityRepositoryInterface
{
    /**
     * @var int
     */
    private $entityPerPage;

    /**
     * @var string
     */
    private $entityStoragePath;

    /**
     * @param string $entityStoragePath
     * @param int $entityPerPage
     */
    public function __construct(string $entityStoragePath, int $entityPerPage)
    {
        $this->entityStoragePath = $entityStoragePath;
        $this->entityPerPage = $entityPerPage;
    }

    /**
     * @inheritdoc
     */
    public function getById(Id $entityId): EntityInterface
    {
        $file = @file_get_contents($this->entityStoragePath.$entityId->getValue());

        if (false === $file) {
            throw new EntityNotFoundException('Invalid id value!');
        }

        if (!$entity = unserialize($file, ['allowed_classes' => true])) {
            throw new EntityNotFoundException('Failed to produce un serialize entity!');
        }

        return $entity;
    }

    /**
     * @inheritdoc
     */
    public function getCount(): int
    {
        return iterator_count(new FilesystemIterator($this->entityStoragePath, FilesystemIterator::SKIP_DOTS));
    }

    /**
     * @inheritdoc
     */
    public function getCollection(int $page, ?string $orderBy = null, ?string $order = null): array
    {
        $entity = [];

        foreach (glob($this->entityStoragePath.'*') as $file) {
            $entity[] = unserialize(file_get_contents($file), ['allowed_classes' => true]);
        }

        if ($orderBy && $order) {
            $methodName = explode('_', $orderBy);
            array_map(static function($path) {
                ucfirst($path);
            }, $methodName);
            $method = 'get'.implode('', $methodName);

            if (OrderingService::ASC === $order) {
                uasort($entity, static function (EntityInterface $a, EntityInterface $b) use ($method) {
                    return strcmp(strtolower($a->$method()), strtolower($b->$method()));
                });
            } else {
                uasort($entity, static function (EntityInterface $b, EntityInterface $a) use ($method) {
                    return strcmp(strtolower($a->$method()), strtolower($b->$method()));
                });
            }
        }

        return array_slice($entity, ($page - 1) * $this->entityPerPage, $this->entityPerPage);
    }

    /**
     * @inheritdoc
     */
    public function add(EntityInterface $entity): Id
    {
        $entityId = $entity->getId();

        if (!$entityId->getValue()) {
            $entityId->setValue(uniqid('id', true));
            $entity->setId($entityId);
        }

        file_put_contents($this->entityStoragePath.'/'.$entityId->getValue(), serialize($entity));

        return $entityId;
    }

    /**
     * @inheritDoc
     */
    public function remove(Id $entityId): void
    {
        unlink($this->entityStoragePath.'/'.$entityId->getValue());
    }
}
