<?php declare(strict_types=1);

namespace App\Exceptions;

/**
 * Class Handler
 * @package App\Exceptions
 */
final class Handler
{
    /**
     * @var array
     */
    private $shouldNotBeLogged = [
        ValidationException::class,
        BadCredentialsException::class,
        UnauthorizedException::class,
    ];

    /**
     * @return array
     */
    public function shouldNotBeLogged(): array
    {
        return $this->shouldNotBeLogged;
    }
}