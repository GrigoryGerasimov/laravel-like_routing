<?php

declare(strict_types=1);

error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

use GrigoryGerasimov\LaraLikeRouting\Bootstrap\App;

require_once('../vendor/autoload.php');

App::run();