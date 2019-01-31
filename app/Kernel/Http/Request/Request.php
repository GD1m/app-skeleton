<?php declare(strict_types=1);

namespace App\Kernel\Http\Request;

use App\Entity\User;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface Request
 * @package App\Kernel\Http\Request
 */
interface Request
{
    /**
     * @return ServerRequestInterface
     */
    public function serverRequest(): ServerRequestInterface;

    /**
     * Retrieve GET parameter
     *
     * @param string $param
     * @return mixed|null
     */
    public function get(string $param);

    /**
     * Retrieve POST parameter
     *
     * @param string $param
     * @return string|array|null
     */
    public function post(string $param);

    /**
     * @return User
     */
    public function getUser(): User;

    /**
     * @param User $user
     */
    public function setUser(User $user);
}