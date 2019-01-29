<?php declare(strict_types=1);

namespace App\Services\Action;

use App\Entity\Action;
use App\Entity\TodoList;
use App\Services\Validation\Validator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class CreateActionService
 * @package App\Services\TodoList
 */
final class CreateActionService
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
     * @param TodoList $todoList
     * @return Action
     * @throws \App\Exceptions\ValidationException
     */
    public function create(string $title = null, TodoList $todoList): Action
    {
        $this->validate($title);

        $now = new DateTime();

        $action = new Action();

        $action->setTitle($title);
        $action->setTodoList($todoList);
        $action->setCompleted(false);
        $action->setCreatedAt($now);
        $action->setUpdatedAt($now);

        $todoList->touch();

        $this->entityManager->persist($action);

        $this->entityManager->flush();

        return $action;
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