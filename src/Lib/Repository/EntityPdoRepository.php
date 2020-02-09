<?php

namespace Todo\Lib\Repository;

use PDO;
use PDOException;
use Todo\Lib\Exceptions\PdoErrorsException;
use Todo\Lib\Exceptions\EntityNotFoundException;
use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Model\EntityInterface;
use Todo\Model\Id;

class EntityPdoRepository implements EntityRepositoryInterface
{
    private PDO $pdo;
    private EntityFactoryInterface $entityFactory;
    private int $entityPerPage;
    private string $entityName;

    public function __construct(Pdo $pdo, EntityFactoryInterface $entityFactory, int $entityPerPage, string $entityName)
    {
        $this->pdo = $pdo;
        $this->entityFactory = $entityFactory;
        $this->entityPerPage = $entityPerPage;
        $this->entityName = $entityName;
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
        $id = $entityId->getValue();

        $sth = $this->pdo->prepare("SELECT id, user_name, email, text, status FROM $this->entityName WHERE id = :id;");
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();

        if (!$entity = $sth->fetch(PDO::FETCH_ASSOC)) {
            throw new EntityNotFoundException();
        }

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
        return  $this->pdo->query("SELECT COUNT(id) FROM $this->entityName;")->fetchColumn();
    }

    /**
     * @param EntityInterface $entity
     *
     * @return Id
     *
     * @throws PdoErrorsException
     */
    public function add(EntityInterface $entity): Id
    {
        $entityId = $entity->getId()->getValue();
        $userName = $entity->getUserName();
        $email = $entity->getEmail();
        $text = $entity->getText();
        $status = $entity->getStatus();

        if ($entityId) {
            $sth = $this->pdo->prepare("UPDATE $this->entityName SET user_name = :userName, email = :email, text = :text, status = :status WHERE id = :id;");
            $sth->bindParam(':id', $entityId, PDO::PARAM_INT);
        } else {
            $sth = $this->pdo->prepare("INSERT INTO $this->entityName (user_name, email, text, status) VALUES(:userName, :email, :text, :status);");
        }

        $sth->bindParam(':userName', $userName);
        $sth->bindParam(':email', $email);
        $sth->bindParam(':text', $text);
        $sth->bindParam(':status', $status);

        try {
            $sth->execute();
        } catch (PDOException $exception) {
            throw new PdoErrorsException($exception->getMessage());
        }

        return new Id($this->pdo->lastInsertId());
    }

    public function remove(Id $entityId): void
    {
        $id = $entityId->getValue();

        $sth = $this->pdo->prepare("DELETE FROM $this->entityName WHERE id = :id;");
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();
    }

    private function generateId(int $count = null): Id
    {
        if (!$count) {
            $count = $this->getCount();
        }

        return new Id(++$count);
    }

    private function generateIdByPage(int $page): int
    {
        return (($page - 1) * $this->entityPerPage) + 1;
    }
}
