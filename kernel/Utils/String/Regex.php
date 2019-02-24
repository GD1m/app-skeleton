<?php declare(strict_types=1);

namespace Kernel\Utils\String;

/**
 * Class Regex
 *
 * @package Kernel\Utils\String
 */
final class Regex
{
    /**
     * @return string
     */
    public static function uuid4(): string
    {
        return '\w{8}-\w{4}-\w{4}-\w{4}-\w{12}';
    }
}