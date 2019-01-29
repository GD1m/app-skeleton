<?php declare(strict_types=1);

namespace App\Kernel\Http\Middleware;

use App\Kernel\Http\Request\RequestHandlerFactory;
use App\Kernel\Http\Response\ErrorResponse;
use FastRoute\Dispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class ProcessRequest
 * @package App\Kernel\Http\Middleware
 */
final class DispatchRequest implements MiddlewareInterface
{
    /**
     * @var Dispatcher FastRoute dispatcher
     */
    private $router;

    /**
     * Set the Dispatcher instance and optionally the response factory to return the error responses.
     * @param Dispatcher $router
     */
    public function __construct(Dispatcher $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $this->router->dispatch($request->getMethod(), $request->getUri()->getPath());

        if ('OPTIONS' === $request->getMethod()) {
            return $this->createResponse(200)
                ->withHeader('Access-Control-Allow-Methods', implode(', ', $route[1] ?? []) . ', OPTIONS');
        }

        if ($route[0] === Dispatcher::NOT_FOUND) {
            return $this->createResponse(404, [
                'error' => 'Page not found',
            ]);
        }

        if ($route[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            return $this->createResponse(405, [
                'error' => 'Method not allowed'
            ])
                ->withHeader('Access-Control-Allow-Methods', implode(', ', $route[1]));
        }

        $request = $request->withAttribute('params', $route[2]);

        $request = $this->setHandler($request, $route[1]);

        return $handler->handle($request);
    }

    /**
     * Set the handler reference on the request.
     *
     * @param ServerRequestInterface $request
     * @param mixed $handler
     * @return ServerRequestInterface
     */
    private function setHandler(ServerRequestInterface $request, $handler): ServerRequestInterface
    {
        return $request->withAttribute(RequestHandlerFactory::REQUEST_HANDLER_ATTRIBUTE, $handler);
    }

    /**
     * @param int $statusCode
     * @param array $errorData
     * @return ResponseInterface
     */
    private function createResponse(int $statusCode, $errorData = []): ResponseInterface
    {
        $response = new ErrorResponse($errorData, $statusCode);

        return $response->getInstance();
    }
}