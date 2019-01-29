<?php declare(strict_types=1);

namespace App\Services\TodoList;

use App\Entity\TodoList;
use App\Exceptions\TodoListNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

/**
 * Class SearchTodoListsService
 * @package App\Services\TodoList
 */
final class SearchTodoListsService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param UuidInterface $id
     * @param UuidInterface $userId
     * @return object|TodoList
     * @throws TodoListNotFoundException
     */
    public function searchByIdAndUserId(UuidInterface $id, UuidInterface $userId): TodoList
    {
        $todoList = $this->entityManager
            ->getRepository(TodoList::class)
            ->findOneBy([
                'id' => $id,
                'user' => $userId,
            ]);

        if (!$todoList) {
            throw new TodoListNotFoundException(sprintf('Id: %s, UserId: %s', $id, $userId));
        }

        return $todoList;
    }
}