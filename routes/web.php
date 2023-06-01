<?php

declare(strict_types=1);

use GrigoryGerasimov\LaraLikeRouting\Core\Routing\Router;
use GrigoryGerasimov\LaraLikeRouting\Http\Controllers\RouteControllers\RouteController;

Router::get('/articles/{article}/test/{test}', [RouteController::class, 'index'])->name('articles.index')->middleware('jwt.auth');