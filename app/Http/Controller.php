<?php declare(strict_types=1);

namespace App\Http;

/**
 * Class Controller
 * @package App\Http
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