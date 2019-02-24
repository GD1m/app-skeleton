<?php declare(strict_types=1);

namespace Kernel\Http\Request;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ServerRequestFactory;

/**
 * Class RequestFactory
 * @package Kernel\Http\Request
 */
final class RequestFactory
{
    /**
     * @return ServerRequestInterface
     */
    public function __invoke(): ServerRequestInterface
    {
        $parsedBody = json_decode(
            file_get_contents('php://input'),
            true
        );

        return ServerRequestFactory::fromGlobals(
            null,
            null,
            $parsedBody
        );
    }
}