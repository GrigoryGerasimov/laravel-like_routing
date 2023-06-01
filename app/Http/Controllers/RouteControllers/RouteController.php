<?php

declare(strict_types=1);

namespace GrigoryGerasimov\LaraLikeRouting\Http\Controllers\RouteControllers;

use GrigoryGerasimov\LaraLikeRouting\Http\Controllers\Controller;

class RouteController extends Controller
{
    public function index(): void
    {
        include(__DIR__.'/../../../../resources/views/home.php');
    }
}