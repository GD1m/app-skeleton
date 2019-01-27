<?php declare(strict_types=1);

use App\Kernel\Http\Kernel as HttpKernel;
use App\Kernel\Http\Middleware\DispatchRequest;
use App\Kernel\Http\Middleware\HandleRoute;
use App\Kernel\Http\Request\Request;
use App\Kernel\Http\Request\RequestFactory;
use App\Kernel\Http\Request\RequestHandlerFactory;
use App\Kernel\Http\Request\RequestImpl;
use App\Kernel\Http\Response\ControllerResponseFactory;
use App\Kernel\Http\Router\RouterFactory;
use FastRoute\Dispatcher;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\ResponseFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use function DI\create;
use function DI\factory;
use function DI\get;

return [
    // Http:
    Dispatcher::class => factory(RouterFactory::class),

    ServerRequestInterface::class => factory(RequestFactory::class),

    Request::class => create(RequestImpl::class)->constructor(
        get(ServerRequestInterface::class)
    ),

    RequestHandlerInterface::class => factory(RequestHandlerFactory::class),

    HttpKernel::class => create(HttpKernel::class)->constructor(
        get(RequestHandlerInterface::class)
    ),

    ResponseFactoryInterface::class => create(ResponseFactory::class),

    ControllerResponseFactory::class => create(ControllerResponseFactory::class)->constructor(
        get(ResponseFactoryInterface::class)
    ),

    SapiEmitter::class => create(SapiEmitter::class),

    // Middlewares:
    DispatchRequest::class => create(DispatchRequest::class)->constructor(
        get(Dispatcher::class)
    ),

    HandleRoute::class => create(HandleRoute::class)->constructor(
        get(ContainerInterface::class),
        get(ControllerResponseFactory::class)
    ),
];
