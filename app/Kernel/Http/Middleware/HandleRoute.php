<?php declare(strict_types=1);

namespace App\Kernel\Http\Middleware;

use App\Http\App\V1\Controller\Controller;
use App\Kernel\Http\Request\RequestHandlerFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

/**
 * Class HandleRoute
 * @package App\Kernel\Http\Middlewares
 */
final class HandleRoute implements MiddlewareInterface
{
    private const HANDLER_DELIMITER = '@';

    /**
     * @var string
     */
    private $controllerNamespace = '\\App\\Http\\App\\V1\\Controller\\';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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

        if (!\is_string($requestHandler) || false === strpos($requestHandler, self::HANDLER_DELIMITER)) {
            throw new RuntimeException(sprintf('Invalid request handler: %s', \gettype($requestHandler)));
        }

        [$class, $method] = explode(self::HANDLER_DELIMITER, $requestHandler, 2);

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
    ): ServerRequestInterface
    {
        $request = $request->withAttribute(RequestHandlerFactory::CONTROLLER_ATTRIBUTE, $controller);
        $request = $request->withAttribute(RequestHandlerFactory::METHOD_ATTRIBUTE, $method);

        return $request;
    }
}