<?php declare(strict_types=1);

namespace App\Services\TodoList;

use App\Entity\Action;
use App\Entity\TodoList;
use App\Entity\User;
use App\Repository\ActionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

/**
 * Class ClearCompletedActionsService
 * @package App\Services\TodoList
 */
final class ClearCompletedActionsService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var GetTodoListService
     */
    private $getTodoListService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param GetTodoListService $getTodoListService
     */
    public function __construct(EntityManagerInterface $entityManager, GetTodoListService $getTodoListService)
    {
        $this->entityManager = $entityManager;
        $this->getTodoListService = $getTodoListService;
    }

    /**
     * @param User $user
     * @param UuidInterface $todoListId
     * @return \App\Entity\TodoList
     * @throws \App\Exceptions\TodoListNotFoundException
     * @throws \App\Exceptions\ValidationException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function clear(User $user, UuidInterface $todoListId): TodoList
    {
        $todoList = $this->getTodoListService->getWithActions(
            $user,
            $todoListId
        );

        /** @var ActionRepository $repository */
        $repository = $this->entityManager->getRepository(Action::class);

        $repository->deleteCompletedActionsByTodoListId($todoListId);

        $this->entityManager->refresh($todoList);

        $todoList->touch();

        $this->entityManager->flush();

        return $todoList;
    }
}