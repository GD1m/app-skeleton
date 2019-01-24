<?php declare(strict_types=1);

namespace App\Kernel\Http\Request;

use App\Kernel\Http\Middleware\HandleRoute;
use Middlewares\FastRoute;
use Psr\Container\ContainerInterface;
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
        $resolver = function ($middleware) use ($container) {
            return $container->get($middleware);
        };

        return new Relay(
            self::middlewares(),
            $resolver
        );
    }

    /**
     * @return array
     */
    private static function middlewares(): array
    {
        return [
            FastRoute::class,
            HandleRoute::class,
        ];
    }
}