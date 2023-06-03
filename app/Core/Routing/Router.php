<?php

declare(strict_types=1);

namespace GrigoryGerasimov\LaraLikeRouting\Core\Routing;

use GrigoryGerasimov\LaraLikeRouting\Core\Exceptions\Route\{
    InvalidRouteException,
    InvalidRouteTypeException
};
use GrigoryGerasimov\LaraLikeRouting\Core\Exceptions\RouteController\{
    InvalidRouteControllerException,
    InvalidRouteControllerTypeException
};

class Router
{
    protected static RouterConfig $routerConfig;

    /** @var array<RouterConfig>  */
    protected static array $GETs = [];

    /** @var array<RouterConfig> */
    protected static array $POSTs = [];

    /** @var array<RouterConfig> */
    protected static array $PUTs = [];

    /** @var array<RouterConfig> */
    protected static array $PATCHs = [];

    /** @var array<RouterConfig> */
    protected static array $DELETEs = [];

    /** @return array<RouterConfig> */
    public static function retrieveGETs(): array
    {
        return self::$GETs;
    }

    public static function retrievePOSTs(): array
    {
        return self::$POSTs;
    }

    private static function init(string $route, array|string $controller, array &$routerConfigCollection): RouterConfig
    {
        try {
            if (!isset($route)) {
                throw new InvalidRouteException('Invalid route');
            } else if (gettype($route) !== 'string') {
                throw new InvalidRouteTypeException('Invalid route type');
            }
            if (empty($controller)) {
                throw new InvalidRouteControllerException('Invalid route controller');
            } else if (gettype($controller) !== 'array' & gettype($controller) !== 'string') {
                throw new InvalidRouteControllerTypeException('Invalid route controller type');
            }
            [$controllerClass, $controllerAction] = gettype($controller) === 'array' ? $controller : explode('@', $controller);
        } catch (InvalidRouteException | InvalidRouteTypeException | InvalidRouteControllerTypeException | InvalidRouteControllerException $e) {
            die($e->getMessage());
        } catch (\Throwable $e) {
            error_log($e->getTraceAsString());
        }

        self::$routerConfig = new RouterConfig($route, $controllerClass, $controllerAction);
        $routerConfigCollection[] = self::$routerConfig;
        return self::$routerConfig;
    }

    public static function get(string $route, array|string $controller): RouterConfig
    {
        return self::init($route, $controller, self::$GETs);
    }

    public static function post(string $route, array|string $controller): RouterConfig
    {
        return self::init($route, $controller, self::$POSTs);
    }
}