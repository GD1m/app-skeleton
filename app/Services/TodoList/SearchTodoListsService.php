<?php declare(strict_types=1);

namespace App\Services\TodoList;

use App\Entity\TodoList;
use App\Exceptions\TodoListNotFoundException;
use App\Repository\TodoListRepository;
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
    public function findByIdAndUserIdOrFail(UuidInterface $id, UuidInterface $userId): TodoList
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

    /**
     * @param UuidInterface $id
     * @param UuidInterface $userId
     * @param bool|null $completed
     * @return TodoList
     * @throws TodoListNotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findWithActionsOrFail(UuidInterface $id, UuidInterface $userId, bool $completed = null): TodoList
    {
        /** @var TodoListRepository $repository */
        $repository = $this->entityManager->getRepository(TodoList::class);

        $todoList = $repository->findWithActionsByUserAndCompletedState($id, $userId, $completed);

        if (!$todoList) {
            throw new TodoListNotFoundException(sprintf('Id: %s, UserId: %s', $id, $userId));
        }

        return $todoList;
    }
}