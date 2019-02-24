<?php declare(strict_types=1);

namespace App\Services\Auth;

use App\Entity\User;
use App\Exceptions\BadCredentialsException;
use App\Services\Session\SessionFactory;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class LoginUserService
 * @package App\Services\Auth
 */
final class LoginUserService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var SessionFactory
     */
    private $sessionFactory;

    /**
     * @param EntityManagerInterface $entityManager
     * @param SessionFactory $sessionFactory
     */
    public function __construct(EntityManagerInterface $entityManager, SessionFactory $sessionFactory)
    {
        $this->entityManager = $entityManager;
        $this->sessionFactory = $sessionFactory;
    }

    /**
     * @param string $username
     * @param string $password
     * @return User
     * @throws BadCredentialsException
     * @throws \App\Exceptions\InfiniteLoopException
     */
    public function login(string $username, string $password): User
    {
        $user = $this->findUserByName($username);

        if (!$username || !$password || !$user) {
            $this->showBadCredentials();
        }

        if (!$this->tryToLogin($user, $password)) {
            $this->showBadCredentials();
        }

        $this->entityManager->persist(
            $this->sessionFactory->new($user)
        );

        $this->entityManager->flush();

        return $user;
    }

    /**
     * @param string $username
     * @return User|null|object
     */
    private function findUserByName(string $username): ? User
    {
        return $this->entityManager
            ->getRepository(User::class)
            ->findOneBy([
                'username' => $username,
            ]);
    }

    /**
     * @param User $user
     * @param string $password
     * @return bool
     */
    private function tryToLogin(User $user, string $password): bool
    {
        return password_verify($password, $user->getPassword());
    }

    /**
     * @throws BadCredentialsException
     */
    private function showBadCredentials(): void
    {
        throw new BadCredentialsException();
    }
}