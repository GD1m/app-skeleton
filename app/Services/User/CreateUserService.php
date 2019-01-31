<?php declare(strict_types=1);

namespace App\Services\User;

use App\Entity\User;
use DateTime;

/**
 * Class CreateUserService
 * @package App\Services\User
 */
final class CreateUserService
{
    /**
     * @param string $username
     * @param $password
     * @return User
     */
    public function make(string $username, $password): User
    {
        $user = new User();

        $user->setUsername($username);
        $user->setPassword(
            password_hash($password, PASSWORD_DEFAULT)
        );
        $user->setCreatedAt(new DateTime());

        return $user;
    }
}