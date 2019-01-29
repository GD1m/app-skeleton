<?php declare(strict_types=1);

namespace App\Services\TodoList;

use App\Entity\TodoList;
use App\Entity\User;
use App\Services\Validation\Validator;
use Ramsey\Uuid\UuidInterface;

/**
 * Class GetTodoListService
 * @package App\Services\TodoList
 */
final class GetTodoListService
{
    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var SearchTodoListsService
     */
    private $searchTodoListsService;

    /**
     * @param Validator $validator
     * @param SearchTodoListsService $searchTodoListsService
     */
    public function __construct(
        Validator $validator,
        SearchTodoListsService $searchTodoListsService
    ) {
        $this->validator = $validator;
        $this->searchTodoListsService = $searchTodoListsService;
    }

    /**
     * @param User $user
     * @param UuidInterface $id
     * @param string|bool|null $completed
     * @return TodoList
     * @throws \App\Exceptions\TodoListNotFoundException
     * @throws \App\Exceptions\ValidationException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function get(User $user, UuidInterface $id, $completed = null): TodoList
    {
        $this->validate($completed);

        if (null !== $completed) {
            $completed = (bool)$completed;
        }

        return $this->searchTodoListsService
            ->findWithActionsOrFail($id, $user->getId(), $completed);
    }

    /**
     * @param string|bool|null $completed
     * @throws \App\Exceptions\ValidationException
     */
    private function validate($completed = null): void
    {
        $this->validator
            ->setData([
                'completed' => $completed,
            ])
            ->setRules([
                'completed' => 'in:1,0',
            ])
            ->validate();
    }
}