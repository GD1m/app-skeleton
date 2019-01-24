<?php declare(strict_types=1);

namespace App\Kernel\Http\Request;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ServerRequestFactory;

/**
 * Class RequestFactory
 * @package App\Kernel\Http\Request
 */
final class RequestFactory
{
    /**
     * @return ServerRequestInterface
     */
    public static function make(): ServerRequestInterface
    {
        return ServerRequestFactory::fromGlobals();
    }
}