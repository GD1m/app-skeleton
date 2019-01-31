<?php declare(strict_types=1);

namespace App\Exceptions;

/**
 * Class BadCredentialsException
 * @package App\Exceptions
 */
final class BadCredentialsException extends \Exception implements Responsible
{
    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return 401;
    }

    /**
     * @return array
     */
    public function getErrorData(): array
    {
        return [
            'error' => 'Invalid Username or Password',
        ];
    }
}