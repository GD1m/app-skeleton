<?php declare(strict_types=1);

namespace App\Kernel\Http\Request;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Request
 * @package App\Kernel\Http\Request
 */
final class RequestImpl implements Request
{
    /**
     * @var ServerRequestInterface
     */
    private $serverRequest;

    /**
     * @param ServerRequestInterface $serverRequest
     */
    public function __construct(ServerRequestInterface $serverRequest)
    {
        $this->serverRequest = $serverRequest;
    }

    /**
     * @return ServerRequestInterface
     */
    public function serverRequest(): ServerRequestInterface
    {
        return $this->serverRequest;
    }

    /**
     * @param string $param
     * @return mixed|null
     */
    public function get(string $param)
    {
        return $this->serverRequest->getQueryParams()[$param] ?? null;
    }

    /**
     * @param string $param
     * @return mixed|null
     */
    public function post(string $param)
    {
        return $this->serverRequest->getParsedBody()[$param] ?? null;
    }
}