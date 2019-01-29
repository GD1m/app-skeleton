<?php declare(strict_types=1);

namespace App\Kernel\Http\Middleware;

use App\Entity\Session;
use App\Entity\User;
use App\Http\App\V1\Controller\Controller;
use App\Exceptions\UnauthorizedException;
use App\Kernel\Http\Request\Request;
use App\Kernel\Http\Request\RequestHandlerFactory;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class AuthorizeRequest
 * @package App\Kernel\Http\Middleware
 */
final class AuthorizeRequest implements MiddlewareInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ContainerInterface $container
     */
    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $serverRequest, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Controller $controller */
        $controller = $serverRequest->getAttribute(RequestHandlerFactory::CONTROLLER_ATTRIBUTE);
        $method = $serverRequest->getAttribute(RequestHandlerFactory::METHOD_ATTRIBUTE);

        if (\in_array($method, $controller->shouldBeAuthorized(), true)) {
            $user = $this->authorize($serverRequest);

            $request = $this->container->get(Request::class);

            $request->setUser($user);
        }

        return $handler->handle($serverRequest);
    }

    /**
     * @param ServerRequestInterface $request
     * @return User
     * @throws UnauthorizedException
     */
    private function authorize(ServerRequestInterface $request): User
    {
        if (!$token = $this->getBearerToken($request)) {
            throw new UnauthorizedException();
        }

        $session = $this->getSessionByToken($token);

        if (!$session) {
            throw new UnauthorizedException();
        }

        return $session->getUser();

    }

    /**
     * @param ServerRequestInterface $request
     * @return null|string
     */
    private function getBearerToken(ServerRequestInterface $request): ? string
    {
        $token = $request->getHeader('Authorization')[0] ?? null;

        if ($token && 0 === strpos($token, 'Bearer ')) {
            $token = substr($token, 7);
        }

        return $token;
    }

    /**
     * @param string $token
     * @return Session|null|object
     */
    private function getSessionByToken(string $token): ? Session
    {
        return $this->entityManager
            ->getRepository(Session::class)
            ->findOneBy([
                'token' => $token,
            ]);
    }
}