<?php declare(strict_types=1);

namespace App\Kernel\Http\Middleware;

use App\Http\App\V1\Controller\Controller;
use App\Kernel\Http\Response\ControllerResponseFactory;
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
     * @var string Attribute name for handler reference
     */
    private $handlerAttribute = 'request-handler';

    /**
     * @var ControllerResponseFactory
     */
    private $responseFactory;

    /**
     * @param ContainerInterface $container
     * @param ControllerResponseFactory $responseFactory
     */
    public function __construct(ContainerInterface $container, ControllerResponseFactory $responseFactory)
    {
        $this->container = $container;
        $this->responseFactory = $responseFactory;
    }

    /**
     * Process a server request and return a response.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $requestHandler = $request->getAttribute($this->handlerAttribute);

        if (empty($requestHandler)) {
            throw new RuntimeException('Empty request handler');
        }

        if (\is_string($requestHandler) && false !== strpos($requestHandler, self::HANDLER_DELIMITER)) {
            $responseContent = $this->callController($requestHandler);

            return $this->processResponseContent($responseContent);
        }

        throw new RuntimeException(sprintf('Invalid request handler: %s', \gettype($requestHandler)));
    }

    /**
     * @param $requestHandler
     * @return ResponseInterface|array|string|mixed
     */
    private function callController(string $requestHandler)
    {
        [$class, $method] = explode(self::HANDLER_DELIMITER, $requestHandler, 2);

        $class = $this->controllerNamespace . $class;

        $controller = $this->container->get($class);

        if (!($controller instanceof Controller)) {
            throw new RuntimeException(sprintf('Controllers must be extended from %s', Controller::class));

        }

        return $controller->{$method}();
    }

    /**
     * @param $responseContent
     * @return ResponseInterface
     */
    private function processResponseContent($responseContent): ResponseInterface
    {
        return $this->responseFactory->fromContent($responseContent);
    }
}