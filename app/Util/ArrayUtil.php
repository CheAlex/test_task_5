<?php

declare(strict_types=1);

namespace App\Util;

class ArrayUtil
{
    /**
     * @throws \RuntimeException
     */
    public static function getByPath(array $array, string $path): mixed
    {
        if (isset($array[$path])) {
            return $array[$path];
        }

        foreach (explode('.', $path) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                throw new \RuntimeException(sprintf('Value by path "%s" not found', $path));
            }

            $array = $array[$segment];
        }

        return $array;
    }

    public static function hasByPath(array $array, string $path): bool
    {
        if ([] === $array) {
            return false;
        }

        if (array_key_exists($path, $array)) {
            return true;
        }

        foreach (explode('.', $path) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return false;
            }

            $array = $array[$segment];
        }

        return true;
    }
}
