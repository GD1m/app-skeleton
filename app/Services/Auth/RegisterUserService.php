<?php declare(strict_types=1);

namespace App\Services\Auth;

use App\Entity\Session;
use App\Entity\User;
use App\Services\Session\SessionFactory;
use App\Services\User\CreateUserService;
use App\Services\Validation\Validator;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class RegisterUserService
 * @package App\Services\Auth
 */
final class RegisterUserService
{
    /**
     * @var Validator
     */
    private $validator;
    /**
     * @var CreateUserService
     */
    private $createUserService;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var SessionFactory
     */
    private $sessionFactory;

    /**
     * @param Validator $validator
     * @param CreateUserService $createUserService
     * @param EntityManagerInterface $entityManager
     * @param SessionFactory $sessionFactory
     */
    public function __construct(
        Validator $validator,
        CreateUserService $createUserService,
        EntityManagerInterface $entityManager,
        SessionFactory $sessionFactory
    ) {
        $this->validator = $validator;
        $this->createUserService = $createUserService;
        $this->entityManager = $entityManager;
        $this->sessionFactory = $sessionFactory;
    }

    /**
     * @param string|null $username
     * @param string|null $password
     * @param string|null $confirmPassword
     * @return User
     * @throws \App\Exceptions\ValidationException
     * @throws \App\Exceptions\InfiniteLoopException
     */
    public function register(string $username = null, string $password = null, string $confirmPassword = null): User
    {
        $this->validate($username, $password, $confirmPassword);

        $user = $this->createUserService->make($username, $password);

        $this->entityManager->persist($user);

        $this->entityManager->persist(
            $this->createSession($user)
        );

        $this->entityManager->flush();

        return $user;
    }


    /**
     * @param string|null $username
     * @param string|null $password
     * @param string|null $confirmPassword
     * @throws \App\Exceptions\ValidationException
     */
    private function validate(string $username = null, string $password = null, string $confirmPassword = null): void
    {
        $this->validator
            ->setData([
                'username' => $username,
                'password' => $password,
                'confirm_password' => $confirmPassword,
            ])
            ->setRules([
                'username' => 'required|min:4|max:50|unique:users,username',
                'password' => 'required|min:6|max:100',
                'confirm_password' => 'required|same:password',
            ])
            ->validate();
    }

    /**
     * @param $user
     * @return Session
     * @throws \App\Exceptions\InfiniteLoopException
     */
    private function createSession($user): Session
    {
        return $this->sessionFactory->new($user);
    }
}