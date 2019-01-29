<?php declare(strict_types=1);

namespace App\Exceptions;

/**
 * Class TodoListNotFoundException
 * @package App\Exceptions
 */
final class TodoListNotFoundException extends \Exception implements Responsible
{

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return 404;
    }

    /**
     * @return array
     */
    public function getErrorData(): array
    {
        return [
            'error' => 'Todo List not found',
        ];
    }
}