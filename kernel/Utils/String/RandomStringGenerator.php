<?php declare(strict_types=1);

namespace Kernel\Utils\String;

/**
 * Class RandomStringGenerator
 * @package Kernel\Utils\String
 */
final class RandomStringGenerator
{
    /**
     * @param int $length
     * @return string
     * @throws \Exception
     */
    public function generate($length = 16): string
    {
        $string = '';

        while (($len = \strlen($string)) < $length) {
            $size = $length - $len;

            $bytes = random_bytes($size);

            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }
}