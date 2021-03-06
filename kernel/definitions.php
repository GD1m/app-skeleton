<?php declare(strict_types=1);

use Kernel\Database\Entity\EntityManagerFactory;
use Kernel\Env\DotenvFactory;
use Kernel\Exceptions\Handler\ExceptionHandlerFactory;
use Kernel\Http\Kernel as HttpKernel;
use Kernel\Http\Middleware\DispatchRequest;
use Kernel\Http\Middleware\HandleRoute;
use Kernel\Http\Request\Request;
use Kernel\Http\Request\RequestFactory;
use Kernel\Http\Request\RequestHandlerFactory;
use Kernel\Http\Request\RequestImpl;
use Kernel\Http\Resource\FractalFactory;
use Kernel\Http\Response\ControllerResponseFactory;
use Kernel\Http\Router\RouterFactory;
use Kernel\Utils\Logger\LoggerFactory;
use Kernel\Utils\Validation\Rules\UniqueRule;
use Kernel\Utils\Validation\ValidatorFactory;
use Doctrine\ORM\EntityManagerInterface;
use Dotenv\Dotenv;
use FastRoute\Dispatcher;
use League\BooBoo\BooBoo as ErrorHandler;
use League\Fractal\Manager as FractalManager;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Rakit\Validation\Validator;
use Zend\Diactoros\ResponseFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use function DI\create;
use function DI\factory;
use function DI\get;

return [
    // Environment:
    Dotenv::class => factory(DotenvFactory::class),

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
        get(ResponseFactoryInterface::class),
        get(FractalManager::class)
    ),

    SapiEmitter::class => create(SapiEmitter::class),

    FractalManager::class => factory(FractalFactory::class),

    // Middlewares:
    DispatchRequest::class => create(DispatchRequest::class)->constructor(
        get(Dispatcher::class)
    ),

    HandleRoute::class => create(HandleRoute::class)->constructor(
        get(ContainerInterface::class),
        '\\App\\Http\\'
    ),

    // Database:
    EntityManagerInterface::class => factory(EntityManagerFactory::class),

    // Error Handler:
    ErrorHandler::class => factory(ExceptionHandlerFactory::class),

    // Utils:
    Validator::class => factory(ValidatorFactory::class),
    UniqueRule::class => create(UniqueRule::class)->constructor(
        get(EntityManagerInterface::class)
    ),

    LoggerInterface::class => factory(LoggerFactory::class),
];
