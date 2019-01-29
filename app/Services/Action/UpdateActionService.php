<?php declare(strict_types=1);

namespace App\Services\Action;

use App\Entity\Action;
use App\Entity\User;
use App\Exceptions\ActionNotFoundException;
use App\Repository\ActionRepository;
use App\Services\Validation\Validator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

/**
 * Class UpdateActionService
 * @package App\Services\TodoList
 */
final class UpdateActionService
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
     * @param User $user
     * @param UuidInterface $actionId
     * @param string|null $title
     * @param string|bool|null $completed
     * @return Action
     * @throws ActionNotFoundException
     * @throws \App\Exceptions\ValidationException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function update(User $user, UuidInterface $actionId, string $title = null, $completed = null): Action
    {
        $this->validate($title, $completed);

        /** @var ActionRepository $repository */
        $repository = $this->entityManager->getRepository(Action::class);

        $action = $repository->findOneByUserIdAndIdOrFail($user->getId(), $actionId);

        $action->setTitle($title);
        $action->setUpdatedAt(new DateTime());

        if (null !== $completed) {
            $action->setCompleted((bool)$completed);
        }

        $action->getTodoList()->touch();

        $this->entityManager->persist($action);

        $this->entityManager->flush();

        return $action;
    }

    /**
     * @param string|null $title
     * @param string|bool|null $completed
     * @throws \App\Exceptions\ValidationException
     */
    private function validate(string $title = null, $completed = null): void
    {
        $this->validator
            ->setData([
                'title' => $title,
                'completed' => $completed,
            ])
            ->setRules([
                'title' => 'min:4|max:50',
                'completed' => 'in:1,0',
            ])
            ->validate();
    }
}