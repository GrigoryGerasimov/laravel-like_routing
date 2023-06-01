<?php

declare(strict_types=1);

namespace GrigoryGerasimov\LaraLikeRouting\Core\Routing;

use GrigoryGerasimov\LaraLikeRouting\Core\Exceptions\{
    InvalidRouteControllerTypeException,
    InvalidRouteTypeException,
    InvalidRouteException,
    InvalidRouteControllerException
};

class Router
{
    protected static RouterConfig $routerConfig;
    protected static array $GETs = [];
    protected static array $POSTs = [];
    protected static array $PUTs = [];
    protected static array $PATCHs = [];
    protected static array $DELETEs = [];

    public static function get(string $route, array|string $controller): RouterConfig
    {
        try {
            if (empty($route)) {
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
            error_log($e->getMessage());
        }

        self::$routerConfig = new RouterConfig($route, $controllerClass, $controllerAction);
        self::$GETs[] = self::$routerConfig;

        (new $controllerClass)->$controllerAction();

        return self::$routerConfig;
    }
}