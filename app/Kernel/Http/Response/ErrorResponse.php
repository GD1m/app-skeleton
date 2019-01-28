<?php declare(strict_types=1);

namespace App\Kernel\Http\Response;

use App\Kernel\Application;
use Zend\Diactoros\Response\JsonResponse;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

/**
 * Class ErrorResponse
 * @package App\Kernel\Http\Response
 */
final class ErrorResponse
{
    /**
     * @var JsonResponse
     */
    private $response;

    /**
     * @param iterable $data
     * @param int $statusCode
     */
    public function __construct(iterable $data, int $statusCode = 500)
    {
        $this->response = new JsonResponse($data, $statusCode);
    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function response(): void
    {
        $app = Application::getInstance();

        $emitter = $app->container()->get(SapiEmitter::class);

        $emitter->emit($this->response);
    }
}