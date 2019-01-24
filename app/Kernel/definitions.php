<?php declare(strict_types=1);

use App\Kernel\Http\Kernel as HttpKernel;
use App\Kernel\Http\Request\RequestFactory;
use App\Kernel\Http\Request\RequestHandlerFactory;
use App\Kernel\Http\Router\RouterFactory;
use FastRoute\Dispatcher;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\ResponseFactory;
use Zend\Diactoros\ServerRequest;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use function DI\create;
use function DI\factory;
use function DI\get;

return [
    Dispatcher::class => factory([RouterFactory::class, 'make']),

    ServerRequest::class => factory([RequestFactory::class, 'make']),

    RequestHandlerInterface::class => factory([RequestHandlerFactory::class, 'make']),

    HttpKernel::class => create(HttpKernel::class)->constructor(
        get(RequestHandlerInterface::class)
    ),

    ResponseFactoryInterface::class => create(ResponseFactory::class),

    SapiEmitter::class => create(SapiEmitter::class),
];