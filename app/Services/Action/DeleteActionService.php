<?php declare(strict_types=1);

namespace App\Services\Action;

use App\Entity\Action;
use App\Entity\User;
use App\Repository\ActionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

/**
 * Class DeleteActionService
 * @package App\Services\TodoList
 */
final class DeleteActionService
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
     * @param User $user
     * @param UuidInterface $id
     * @throws \App\Exceptions\ActionNotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function delete(User $user, UuidInterface $id): void
    {
        /** @var ActionRepository $repository */
        $repository = $this->entityManager->getRepository(Action::class);

        $action = $repository->findOneByUserIdAndIdOrFail($user->getId(), $id);

        $this->entityManager->remove($action);

        $this->entityManager->flush();
    }
}