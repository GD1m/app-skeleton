<?php declare(strict_types=1);

namespace App\Exceptions;

use Kernel\Exceptions\ResponsibleException;

/**
 * Class BadCredentialsException
 * @package App\Exceptions
 */
final class BadCredentialsException extends \Exception implements ResponsibleException
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