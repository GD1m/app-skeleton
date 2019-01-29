<?php declare(strict_types=1);

namespace App\Services\TodoList;

use App\Entity\TodoList;
use App\Services\Validation\Validator;
use Doctrine\ORM\EntityManagerInterface;

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
     * @param Validator $validator
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(Validator $validator, EntityManagerInterface $entityManager)
    {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    public function update(TodoList $todoList, string $title = null): void
    {
        $this->validate($title);

        $todoList->setTitle($title);

        $todoList->touch();

        $this->entityManager->persist($todoList);

        $this->entityManager->flush();
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