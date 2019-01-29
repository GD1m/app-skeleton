<?php declare(strict_types=1);

namespace App\Services\Auth;

use App\Entity\Session;
use App\Entity\User;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class LogoutService
 * @package App\Services\Auth
 */
final class LogoutService
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
     */
    public function logout(User $user): void
    {
        /** @var SessionRepository $repository */
        $repository = $this->entityManager->getRepository(Session::class);

        $repository->destroySessions($user->getId());
    }
}