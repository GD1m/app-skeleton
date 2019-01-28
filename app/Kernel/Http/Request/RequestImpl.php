<?php declare(strict_types=1);

namespace App\Kernel\Http\Request;

use App\Entity\User;
use App\Exceptions\PropertyMissedException;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Request
 * @package App\Kernel\Http\Request
 */
final class RequestImpl implements Request
{
    /**
     * @var ServerRequestInterface
     */
    private $serverRequest;

    /**
     * @var User
     */
    private $user;

    /**
     * @param ServerRequestInterface $serverRequest
     */
    public function __construct(ServerRequestInterface $serverRequest)
    {
        $this->serverRequest = $serverRequest;
    }

    /**
     * @return ServerRequestInterface
     */
    public function serverRequest(): ServerRequestInterface
    {
        return $this->serverRequest;
    }

    /**
     * @param string $param
     * @return mixed|null
     */
    public function get(string $param)
    {
        return $this->serverRequest->getQueryParams()[$param] ?? null;
    }

    /**
     * @param string $param
     * @return mixed|null
     */
    public function post(string $param)
    {
        return $this->serverRequest->getParsedBody()[$param] ?? null;
    }

    /**
     * @return User
     * @throws PropertyMissedException
     */
    public function getUser(): User
    {
        if (null === $this->user) {
            throw new PropertyMissedException('User not assigned');
        }

        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}