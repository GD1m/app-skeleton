<?php declare(strict_types=1);

namespace App\Services\TodoList;

use App\Entity\TodoList;
use App\Entity\User;
use App\Services\Validation\Validator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class CreateTodoListService
 * @package App\Services\TodoList
 */
final class CreateTodoListService
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
     * @param Validator $validator
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(Validator $validator, EntityManagerInterface $entityManager)
    {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string|null $title
     * @param User $user
     * @return TodoList
     * @throws \App\Exceptions\ValidationException
     */
    public function create(string $title = null, User $user): TodoList
    {
        $this->validate($title);

        $now = new DateTime();

        $todoList = new TodoList();

        $todoList->setUser($user);
        $todoList->setTitle($title);
        $todoList->setCreatedAt($now);
        $todoList->setUpdatedAt($now);

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