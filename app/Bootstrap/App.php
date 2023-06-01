<?php

declare(strict_types=1);

namespace GrigoryGerasimov\LaraLikeRouting\Bootstrap;

final class App
{
    public static function run(): void
    {
        include_once(__DIR__.'/../../routes/web.php');
    }
}
