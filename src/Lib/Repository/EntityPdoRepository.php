<?php

namespace Todo\Lib\Repository;

use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Lib\Exceptions\NotValidEmailException;
use Todo\Lib\Exceptions\PdoErrorsException;
use Todo\Lib\Exceptions\NotFoundException;
use Todo\Lib\Service\OrderingService;
use Todo\Model\Email;
use Todo\Model\EntityInterface;
use Todo\Model\Id;
use Todo\Model\Status;
use Todo\Model\Text;
use Todo\Model\UserName;
use PDO;
use PDOException;

class EntityPdoRepository implements EntityRepositoryInterface
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var string
     */
    private $entityName;
    /**
     * @var int
     */
    private $entityPerPage;

    /**
     * @var string
     */
    private $entityClassNamespace;

    /**
     * @param PDO $pdo
     * @param string $entityName
     * @param int $entityPerPage
     * @param string $entityClassNamespace
     */
    public function __construct(Pdo $pdo, string $entityName, int $entityPerPage, string $entityClassNamespace)
    {
        $this->pdo = $pdo;
        $this->entityName = strtolower($entityName);
        $this->entityPerPage = $entityPerPage;
        $this->entityClassNamespace = $entityClassNamespace;
    }

    /**
     * @inheritdoc
     */
    public function getEntityById(int $id): EntityInterface
    {
        $sth = $this->pdo->prepare("SELECT * FROM $this->entityName WHERE id = :id;");
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();

        if (!$entity = $sth->fetch(PDO::FETCH_ASSOC)) {
            throw new NotFoundException();
        }

        return $this->createEntity($entity);
    }

    /**
     * @inheritdoc
     */
    public function getCountEntities(): int
    {
        return  $this->pdo->query("SELECT COUNT(id) FROM $this->entityName;")->fetchColumn();
    }

    /**
     * @inheritdoc
     */
    public function getEntities(int $page, ?string $orderBy = null, ?string $order = null): array
    {
        $result = [];

        $orderBy = OrderingService::getOrderBy($orderBy);
        $order = OrderingService::getOrder($order);

        $sth = $this->pdo->prepare("SELECT * FROM $this->entityName ORDER BY $orderBy $order LIMIT :limit OFFSET :offset;");
        $limit = $this->entityPerPage;
        $offset = $this->entityPerPage * ($page - 1);
        $sth->bindParam(':limit', $limit, PDO::PARAM_INT);
        $sth->bindParam(':offset', $offset, PDO::PARAM_INT);
        $sth->execute();

        foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $entity) {
            $result[$entity['id']] = $this->createEntity($entity);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function saveEntity(EntityInterface $entity, ?int $entityId = null): int
    {
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

        return $this->pdo->lastInsertId();
    }

    /**
     * @param int $entityId
     */
    public function deleteEntity(int $entityId): void
    {
        $sth = $this->pdo->prepare("DELETE FROM $this->entityName WHERE id = :id;");
        $sth->bindParam(':id', $entityId, PDO::PARAM_INT);
        $sth->execute();
    }

    /**
     * @param array $entity
     *
     * @return EntityInterface
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     */
    private function createEntity(array $entity): EntityInterface
    {
        $entityName = $this->entityClassNamespace.ucfirst($this->entityName);

        return new $entityName(
            new Id($entity['id']),
            new UserName($entity['user_name']),
            new Email($entity['email']),
            new Text($entity['text']),
            new Status($entity['status'])
        );
    }
}
