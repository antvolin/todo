<?php

namespace Todo\Lib\Repository;

use FilesystemIterator;
use Generator;
use Todo\Lib\Exceptions\EntityNotFoundException;
use Todo\Lib\Service\Ordering\OrderingService;
use Todo\Model\EntityInterface;
use Todo\Model\Id;

class EntityFileRepository implements EntityRepositoryInterface
{
    private int $entityPerPage;
    private string $entityStoragePath;

    public function __construct(string $entityStoragePath, int $entityPerPage)
    {
        $this->entityStoragePath = $entityStoragePath;
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
        $file = @file_get_contents($this->entityStoragePath.$entityId->getValue());

        if (false === $file) {
            throw new EntityNotFoundException('Invalid id value!');
        }

        if (!$entity = unserialize($file, ['allowed_classes' => true])) {
            throw new EntityNotFoundException('Failed to produce un serialize entity!');
        }

        return $entity;
    }

    public function getCount(): int
    {
        return iterator_count(new FilesystemIterator($this->entityStoragePath, FilesystemIterator::SKIP_DOTS));
    }

    public function getCollection(int $page, ?string $orderBy = null, ?string $order = null): array
    {
        $entity = iterator_to_array($this->getFilesIterator());

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

    private function getFilesIterator(): ?Generator
    {
        foreach (glob($this->entityStoragePath.'*') as $file) {
            yield unserialize(file_get_contents($file), ['allowed_classes' => true]);
        }
    }

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

    public function remove(Id $entityId): void
    {
        unlink($this->entityStoragePath.'/'.$entityId->getValue());
    }
}
