<?php

declare(strict_types=1);

namespace GrigoryGerasimov\LaraLikeRouting\Bootstrap;

use GrigoryGerasimov\LaraLikeRouting\Core\Exceptions\Route\InvalidRouteCount;
use GrigoryGerasimov\LaraLikeRouting\Core\Routing\{Router, RouteDispatcher};

final class App
{
    private static function getMethodCollectionName(): string
    {
        return 'retrieve' . $_SERVER['REQUEST_METHOD'] . 's';
    }

    public static function run(): void
    {
        try {
            if (empty(Router::retrieveGETs())) {
                throw new InvalidRouteCount('No routes identified');
            }

            $methodCollection = self::getMethodCollectionName();

            foreach(Router::$methodCollection() as $config) {
                $dispatcher = new RouteDispatcher($config);
                $dispatcher->start();
            }
        } catch (InvalidRouteCount $e) {
            die($e->getMessage());
        } catch (\Throwable $e) {
            error_log($e->getTraceAsString());
        }
    }
}
