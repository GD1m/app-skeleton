<?php declare(strict_types=1);

namespace Kernel\Exceptions;

/**
 * Interface Responsible
 * @package Kernel\Exceptions
 */
interface ResponsibleException
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