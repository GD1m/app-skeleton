<?php declare(strict_types=1);

namespace App\Kernel\Http\Response;

use League\Fractal\Manager;
use League\Fractal\Resource\ResourceInterface;
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
     * @var Manager
     */
    private $fractal;

    /**
     * @param ResponseFactoryInterface $responseFactory
     * @param Manager $fractal
     */
    public function __construct(ResponseFactoryInterface $responseFactory, Manager $fractal)
    {
        $this->responseFactory = $responseFactory;
        $this->fractal = $fractal;
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

        if ($responseContent instanceof ResourceInterface) {

            return new JsonResponse(
                $this->fractal->createData($responseContent)->toArray()
            );
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