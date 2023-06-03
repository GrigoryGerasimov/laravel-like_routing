<?php

declare(strict_types=1);

use GrigoryGerasimov\LaraLikeRouting\Core\Routing\Router;
use GrigoryGerasimov\LaraLikeRouting\Http\Controllers\RouteControllers\RouteController;

Router::get('/articles', [RouteController::class, 'index'])->name('articles.index')->middleware('jwt.auth');
Router::get('/articles/{article}/test/{test}', [RouteController::class, 'show'])->name('articles.show');
Router::get('/articles/create', [RouteController::class, 'create'])->name('articles.create');
Router::post('/articles', [RouteController::class, 'store'])->name('articles.store');