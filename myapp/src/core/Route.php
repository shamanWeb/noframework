<?php

namespace App\core;

class Route
{
    /**
     * Route GET parameter name
     */
    public const ROUTE_PARAM_NAME = 'r';

    /**
     * @return array
     */
    public static function parseRequest(): array
    {
        if (!isset($_GET[self::ROUTE_PARAM_NAME])) {
            return [];
        }

        return explode('/', $_GET[self::ROUTE_PARAM_NAME]);
    }

    /**
     * @param string $route
     * @param array $params
     * @param boolean $absolute
     * @return string
     */
    public static function createUrl($route, array $params = [], $absolute = false): string
    {
        return ($absolute ? App::$baseUrl : '/') . '?' . self::ROUTE_PARAM_NAME . '=' . $route . (count($params) ? '&' . http_build_query($params) : '');
    }
}