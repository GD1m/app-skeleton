<?php declare(strict_types=1);

namespace App\Kernel\Http\Middleware;

use App\Kernel\Http\Request\RequestHandlerFactory;
use App\Kernel\Http\Response\ControllerResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class CallController implements MiddlewareInterface
{
    /**
     * @var ControllerResponseFactory
     */
    private $responseFactory;

    /**
     * @param ControllerResponseFactory $responseFactory
     */
    public function __construct(ControllerResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $controller = $request->getAttribute(RequestHandlerFactory::CONTROLLER_ATTRIBUTE);
        $method = $request->getAttribute(RequestHandlerFactory::METHOD_ATTRIBUTE);

        $params = $request->getAttribute('params');

        $responseContent = \call_user_func_array([$controller, $method], $params);

        return $this->processResponseContent($responseContent);
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