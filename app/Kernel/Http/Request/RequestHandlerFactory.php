<?php declare(strict_types=1);

namespace App\Kernel\Http\Request;

use App\Kernel\Http\Middleware\AuthorizeRequest;
use App\Kernel\Http\Middleware\CallController;
use App\Kernel\Http\Middleware\DispatchRequest;
use App\Kernel\Http\Middleware\HandleRoute;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Relay\Relay;

/**
 * Class RequestHandlerFactory
 * @package App\Kernel\Http\Request
 */
final class RequestHandlerFactory
{
    public const REQUEST_HANDLER_ATTRIBUTE = 'request-handler';
    public const CONTROLLER_ATTRIBUTE = 'controller';
    public const METHOD_ATTRIBUTE = 'method';

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
            AuthorizeRequest::class,
            CallController::class,
        ];
    }
}