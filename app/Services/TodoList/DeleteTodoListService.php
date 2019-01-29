<?php declare(strict_types=1);

namespace App\Services\TodoList;

use App\Entity\TodoList;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class DeleteTodoListService
 * @package App\Services\TodoList
 */
final class DeleteTodoListService
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
     * @param TodoList $todoList
     */
    public function delete(TodoList $todoList): void
    {
        $this->entityManager->remove($todoList);

        $this->entityManager->flush();
    }
}