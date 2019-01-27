<?php declare(strict_types=1);

namespace App\Kernel\Http\Response;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class ControllerResponseFactory
 * @package App\Kernel\Http\Response
 */
final class ControllerResponseFactory
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param $responseContent
     * @return ResponseInterface
     */
    public function fromContent($responseContent): ResponseInterface
    {
        if (\is_array($responseContent)) {
            return new JsonResponse($responseContent);
        }

        if ($responseContent instanceof ResponseInterface) {
            return $responseContent;
        }

        if (\is_string($responseContent)) {
            $response = $this->responseFactory->createResponse()->withHeader('Content-Type', 'text/html');

            $response->getBody()->write($responseContent);

            return $response;
        }

        throw new RuntimeException(sprintf('Unexpected response content type: %s', \gettype($responseContent)));

    }
}