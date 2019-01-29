<?php declare(strict_types=1);

namespace App\Exceptions;

/**
 * Class UnauthorizedException
 * @package App\Kernel\Exceptions
 */
final class UnauthorizedException extends \Exception implements Responsible
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
            'error' => 'Unauthorized',
        ];
    }
}
