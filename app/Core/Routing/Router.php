<?php

declare(strict_types=1);

namespace GrigoryGerasimov\LaraLikeRouting\Core\Routing;

use GrigoryGerasimov\LaraLikeRouting\Core\Exceptions\InvalidRouteControllerException;

class Router
{
    public static function get(string $route, array|string $controller): void
    {
        if ($_SERVER['REQUEST_URI'] === $route) {

            try {
                if (!empty($controller)) {
                    [$controllerClass, $controllerAction] = gettype($controller) === 'array' ? $controller : explode('@', $controller);
                } else {
                    throw new InvalidRouteControllerException('Invalid route controller');
                }
            } catch (InvalidRouteControllerException $e) {
                die($e->getMessage());
            } catch (\Throwable $e) {
                error_log($e->getMessage());
            }

            (new $controllerClass)->$controllerAction();

        }
    }
}