<?php

declare(strict_types=1);

use GrigoryGerasimov\LaraLikeRouting\Core\Routing\Router;
use GrigoryGerasimov\LaraLikeRouting\Http\Controllers\RouteControllers\RouteController;

Router::get('/', [RouteController::class, 'index'])->name('test name')->middleware('test middleware');