<?php

declare(strict_types=1);

namespace GrigoryGerasimov\LaraLikeRouting\Bootstrap;

use GrigoryGerasimov\LaraLikeRouting\Core\Exceptions\Route\InvalidRouteCount;
use GrigoryGerasimov\LaraLikeRouting\Core\Routing\{Router, RouteDispatcher};

final class App
{
    public static function run(): void
    {
        try {
            if (empty(Router::retrieveGETs())) {
                throw new InvalidRouteCount('No routes identified');
            }

            foreach(Router::retrieveGETs() as $GETConfig) {
                $dispatcher = new RouteDispatcher($GETConfig);
                $dispatcher->start();
            }
        } catch (InvalidRouteCount $e) {
            die($e->getMessage());
        } catch (\Throwable $e) {
            error_log($e->getTraceAsString());
        }
    }
}
