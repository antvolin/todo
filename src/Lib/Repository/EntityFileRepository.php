<?php

namespace BeeJeeMVC\Lib\Repository;

use BeeJeeMVC\Lib\Ordering;
use BeeJeeMVC\Model\EntityInterface;
use FilesystemIterator;
use LogicException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

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
    public function getById(int $id): EntityInterface
    {
        $file = file_get_contents($this->entityStoragePath.$id);

        if (false === $file) {
            throw new FileNotFoundException('Invalid id value!');
        }

        $entity = unserialize($file, ['allowed_classes' => true]);

        if (!$entity) {
            throw new LogicException('Failed to produce un serialize entity!');
        }

        return $entity;
    }

    /**
     * @inheritdoc
     */
    public function getCountRows(): int
    {
        return iterator_count(new FilesystemIterator($this->entityStoragePath, FilesystemIterator::SKIP_DOTS));
    }

    /**
     * @inheritdoc
     */
    public function getList(int $page, ?string $orderBy = null, ?string $order = null): array
    {
        $entity = [];

        foreach (glob($this->entityStoragePath.'*') as $file) {
            $entity[basename($file)] = unserialize(file_get_contents($file), ['allowed_classes' => true]);
        }

        if ($orderBy && $order) {
            $method = 'get'.ucfirst($orderBy);

            if (Ordering::ASC === $order) {
                uasort($entity, function (EntityInterface $a, EntityInterface $b) use ($method) {
                    return strcmp(strtolower($a->$method()), strtolower($b->$method()));
                });
            } else {
                uasort($entity, function (EntityInterface $b, EntityInterface $a) use ($method) {
                    return strcmp(strtolower($a->$method()), strtolower($b->$method()));
                });
            }
        }

        return array_slice($entity, ($page - 1) * $this->entityPerPage, $this->entityPerPage);
    }

    /**
     * @inheritdoc
     */
    public function save(EntityInterface $entity, ?int $entityId = null): void
    {
        file_put_contents($this->entityStoragePath.$entity->getId()->getValue(), serialize($entity));
    }
}
