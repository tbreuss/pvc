<?php

declare(strict_types=1);

namespace Tebe\Pvc\Helper;

use InvalidArgumentException;

// @todo implement class properly

class UrlHelper
{
    /**
     * @param mixed $url
     * @return string
     * @throws InvalidArgumentException
     */
    public static function to($url = ''): string
    {
        if (is_array($url)) {
            return static::toRoute($url);
        }
        if (is_string($url)) {
            return $url;
        }
        throw new InvalidArgumentException('Only array or string allowed');
    }

    /**
     * @param array $route
     * @return string
     * @throws InvalidArgumentException
     */
    public static function toRoute(array $route): string
    {
        if (empty($route)) {
            throw new InvalidArgumentException('The given route is empty');
        }

        $urlParts = [$_SERVER['SCRIPT_NAME']];

        $r = trim(array_shift($route), '/');
        if (!empty($r)) {
            $urlParts[] = '/';
            $urlParts[] = $r;
        }

        $anchor = [];
        if (isset($route['#'])) {
            $anchor[] = '#';
            $anchor[] = $route['#'];
            unset($route['#']);
        }

        if (!empty($route)) {
            $query = http_build_query($route);
            $urlParts[] = '?';
            $urlParts[] = $query;
        }

        $urlParts = array_merge($urlParts, $anchor);

        $url = implode('', $urlParts);
        return $url;
    }
}
