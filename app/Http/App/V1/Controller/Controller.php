<?php declare(strict_types=1);

namespace App\Http\App\V1\Controller;

/**
 * Base controller
 *
 * Class Controller
 * @package App\Http\Controller
 */
abstract class Controller
{
    /**
     * @var array
     */
    protected $shouldBeAuthorized = [];

    /**
     * @return array
     */
    public function shouldBeAuthorized(): array
    {
        return $this->shouldBeAuthorized;
    }
}