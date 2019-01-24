<?php declare(strict_types=1);

namespace App\Kernel\Http\Request;

use Middlewares\RequestHandler;
use FastRoute\Dispatcher;
use Middlewares\FastRoute as ValidateRoute;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Relay\Relay;

/**
 * Class RequestHandlerFactory
 * @package App\Kernel\Http\Request
 */
final class RequestHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return RequestHandlerInterface
     */
    public static function make(ContainerInterface $container): RequestHandlerInterface
    {
        return new Relay(
            self::middlewares($container)
        );
    }

    /**
     * @param ContainerInterface $container
     * @return array|MiddlewareInterface[]
     */
    private static function middlewares(ContainerInterface $container): array
    {
        return [
            new ValidateRoute($container->get(Dispatcher::class)),
            new RequestHandler($container)
        ];
    }
}