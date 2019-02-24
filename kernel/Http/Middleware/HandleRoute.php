<?php declare(strict_types=1);

namespace Kernel\Http\Middleware;

use App\Http\Controller;
use Kernel\Http\Request\RequestHandlerFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

/**
 * Class HandleRoute
 * @package Kernel\Http\Middlewares
 */
final class HandleRoute implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $controllerNamespace;

    /**
     * @var string
     */
    private $handlerDelimiter;

    /**
     * @param ContainerInterface $container
     * @param string $controllerNamespace
     * @param string $handlerDelimiter
     */
    public function __construct(
        ContainerInterface $container,
        string $controllerNamespace,
        string $handlerDelimiter = '@'
    ) {
        $this->container = $container;
        $this->controllerNamespace = $controllerNamespace;
        $this->handlerDelimiter = $handlerDelimiter;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $requestHandler = $request->getAttribute(RequestHandlerFactory::REQUEST_HANDLER_ATTRIBUTE);

        if (empty($requestHandler)) {
            throw new RuntimeException('Empty request handler');
        }

        if (!\is_string($requestHandler) || false === strpos($requestHandler, $this->handlerDelimiter)) {
            throw new RuntimeException(sprintf('Invalid request handler: %s', \gettype($requestHandler)));
        }

        [$class, $method] = explode($this->handlerDelimiter, $requestHandler, 2);

        $class = $this->controllerNamespace . $class;

        $controller = $this->container->get($class);

        if (!($controller instanceof Controller)) {
            throw new RuntimeException(sprintf('Controllers must be extended from %s', Controller::class));
        }

        return $handler->handle(
            $this->setControllerData($request, $controller, $method)
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @param Controller $controller
     * @param string $method
     * @return ServerRequestInterface
     */
    private function setControllerData(
        ServerRequestInterface $request,
        Controller $controller,
        string $method
    ): ServerRequestInterface {
        $request = $request->withAttribute(RequestHandlerFactory::CONTROLLER_ATTRIBUTE, $controller);

        return $request->withAttribute(RequestHandlerFactory::METHOD_ATTRIBUTE, $method);
    }
}