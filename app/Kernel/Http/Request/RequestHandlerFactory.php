<?php declare(strict_types=1);

namespace App\Kernel\Http\Request;

use App\Kernel\Http\Middleware\HandleRoute;
use App\Kernel\Http\Middleware\DispatchRequest;
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
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $resolver = function ($middleware) use ($container) {
            return $container->get($middleware);
        };

        return new Relay(
            $this->middlewares(),
            $resolver
        );
    }

    /**
     * @return array
     */
    private function middlewares(): array
    {
        return [
            DispatchRequest::class,
            HandleRoute::class,
        ];
    }
}