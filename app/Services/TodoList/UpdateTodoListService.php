<?php declare(strict_types=1);

namespace App\Services\TodoList;

use App\Entity\TodoList;
use App\Entity\User;
use App\Services\Validation\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

/**
 * Class UpdateTodoListService
 * @package App\Services\TodoList
 */
final class UpdateTodoListService
{
    /**
     * @var Validator
     */
    private $validator;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SearchTodoListsService
     */
    private $searchTodoListsService;

    /**
     * @param Validator $validator
     * @param EntityManagerInterface $entityManager
     * @param SearchTodoListsService $searchTodoListsService
     */
    public function __construct(
        Validator $validator,
        EntityManagerInterface $entityManager,
        SearchTodoListsService $searchTodoListsService
    )
    {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
        $this->searchTodoListsService = $searchTodoListsService;
    }

    /**
     * @param User $user
     * @param UuidInterface $id
     * @param string|null $title
     * @return TodoList
     * @throws \App\Exceptions\TodoListNotFoundException
     * @throws \App\Exceptions\ValidationException
     */
    public function update(User $user, UuidInterface $id, string $title = null): TodoList
    {
        $this->validate($title);

        $todoList = $this->searchTodoListsService->findByIdAndUserIdOrFail($id, $user->getId());

        $todoList->setTitle($title);

        $todoList->touch();

        $this->entityManager->persist($todoList);

        $this->entityManager->flush();

        return $todoList;
    }

    /**
     * @param string|null $title
     * @throws \App\Exceptions\ValidationException
     */
    private function validate(string $title = null): void
    {
        $this->validator
            ->setData([
                'title' => $title,
            ])
            ->setRules([
                'title' => 'required|min:4|max:50',
            ])
            ->validate();
    }
}