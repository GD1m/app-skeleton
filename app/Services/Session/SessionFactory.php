<?php declare(strict_types=1);

namespace App\Services\Session;

use App\Entity\Session;
use App\Entity\User;
use App\Repository\SessionRepository;
use App\Services\Security\TokenGenerator;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class SessionFactory
 * @package App\Services\Session
 */
final class SessionFactory
{
    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param TokenGenerator $tokenGenerator
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(TokenGenerator $tokenGenerator, EntityManagerInterface $entityManager)
    {
        $this->tokenGenerator = $tokenGenerator;
        $this->entityManager = $entityManager;
    }

    /**
     * @param User $user
     * @return Session
     * @throws \App\Exceptions\InfiniteLoopException
     */
    public function new(User $user): Session
    {
        $this->destroyOldSessions($user);

        $token = $this->tokenGenerator->generateUniqueToken(Session::class);

        $session = new Session();

        $session->setUser($user);
        $session->setToken($token);
        $session->setCreatedAt(new \DateTime());

        $user->addSession($session);

        return $session;
    }

    private function destroyOldSessions(User $user): void
    {
        /** @var SessionRepository $repository */
        $repository = $this->entityManager->getRepository(Session::class);

        $repository->destroySessions($user->getId());
    }
}