<?php declare(strict_types=1);

namespace App\Services\Session;

use App\Entity\Session;
use App\Entity\User;
use App\Services\Security\TokenGenerator;

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
     * @param TokenGenerator $tokenGenerator
     */
    public function __construct(TokenGenerator $tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * @param User $user
     * @return Session
     * @throws \App\Exceptions\InfiniteLoopException
     */
    public function new(User $user): Session
    {
        $token = $this->tokenGenerator->generateUniqueToken(Session::class);

        $session = new Session();

        $session->setUser($user);
        $session->setToken($token);
        $session->setCreatedAt(new \DateTime());

        $user->addSession($session);

        return $session;
    }
}