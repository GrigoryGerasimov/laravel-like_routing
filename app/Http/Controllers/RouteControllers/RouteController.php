<?php

declare(strict_types=1);

namespace GrigoryGerasimov\LaraLikeRouting\Http\Controllers\RouteControllers;

use GrigoryGerasimov\LaraLikeRouting\Http\Controllers\Controller;
use GrigoryGerasimov\LaraLikeRouting\Core\View\View;

class RouteController extends Controller
{
    public function index(): View
    {
        $fromSession = $_SESSION['posted'];

        return View::make('articles.index', compact('fromSession'));
    }

    public function show(string $article, string $test): View
    {
        return View::make('home', compact('article','test'));
    }

    public function create(): View
    {
        return View::make('articles.create');
    }

    public function store(): View
    {
        $_SESSION['posted'] = $_POST['title'];

        return View::toRoute('articles.index');
    }
}