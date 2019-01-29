<?php declare(strict_types=1);

namespace App\Exceptions;

/**
 * Interface Responsible
 * @package App\Exceptions
 */
interface Responsible
{
    /**
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * @return array
     */
    public function getErrorData(): array;
}