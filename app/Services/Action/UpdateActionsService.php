<?php declare(strict_types=1);

namespace App\Services\Action;

use App\Entity\Action;
use App\Entity\TodoList;
use App\Entity\User;
use App\Repository\ActionRepository;
use App\Services\TodoList\SearchTodoListsService;
use App\Services\Validation\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

/**
 * Class UpdateActionsService
 * @package App\Services\Action
 */
final class UpdateActionsService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var SearchTodoListsService
     */
    private $searchTodoListsService;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @param EntityManagerInterface $entityManager
     * @param SearchTodoListsService $searchTodoListsService
     * @param Validator $validator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        SearchTodoListsService $searchTodoListsService,
        Validator $validator
    ) {
        $this->searchTodoListsService = $searchTodoListsService;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @param User $user
     * @param UuidInterface $id
     * @param string|null $completed
     * @return TodoList
     * @throws \App\Exceptions\TodoListNotFoundException
     * @throws \App\Exceptions\ValidationException
     */
    public function changeCompletedByTodoListId(User $user, UuidInterface $id, $completed = null): TodoList
    {
        $this->validateCompleted($completed);

        $todoList = $this->searchTodoListsService->findByIdAndUserIdOrFail($id, $user->getId());

        /** @var ActionRepository $repository */
        $repository = $this->entityManager->getRepository(Action::class);

        $repository->changeCompletedStateByTodoListId($id, (bool)$completed);

        $this->entityManager->refresh($todoList);

        $todoList->touch();

        $this->entityManager->flush();

        return $todoList;
    }

    /**
     * @param string|null $completed
     * @throws \App\Exceptions\ValidationException
     */
    private function validateCompleted($completed = null): void
    {
        $this->validator
            ->setData([
                'completed' => $completed,
            ])
            ->setRules([
                'completed' => 'required|in:1,0',
            ])
            ->validate();
    }
}