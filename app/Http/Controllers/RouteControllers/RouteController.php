<?php

declare(strict_types=1);

namespace GrigoryGerasimov\LaraLikeRouting\Http\Controllers\RouteControllers;

use GrigoryGerasimov\LaraLikeRouting\Http\Controllers\Controller;
use GrigoryGerasimov\LaraLikeRouting\Core\View\View;

class RouteController extends Controller
{
    public function index(): void
    {
        View::make('articles.index');
    }

    public function show(string $article, string $test): void
    {
        View::make('home');
        print($article).PHP_EOL;
        print($test).PHP_EOL;
    }
}