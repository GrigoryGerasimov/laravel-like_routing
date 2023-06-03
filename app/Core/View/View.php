<?php

declare(strict_types=1);

namespace GrigoryGerasimov\LaraLikeRouting\Core\View;

use GrigoryGerasimov\LaraLikeRouting\Core\Exceptions\View\{
    MissingViewPath,
    InvalidViewPathException
};
use GrigoryGerasimov\LaraLikeRouting\Core\Routing\RouterConfig;

class View
{
    /** @var array<RouterConfig> */
    private static array $configRoutes = [];

    protected const ROOT = 'resources/views';
    protected static string $viewFullPath;
    protected static array $attributes = [];

    public static function __callStatic(string $name, array $arguments)
    {
        if (method_exists(self::class, $name)) {
            return call_user_func_array([self::class, $name], $arguments);
        }
    }

    public static function make(string $path, ?array $attributes = []): self
    {
        try {
            if (!isset($path)) {
                throw new MissingViewPath('No view path provided');
            }
            if (gettype($path) !== 'string') {
                throw new InvalidViewPathException('Invalid view path');
            }
            if (!empty($attributes)) {
                self::$attributes = $attributes;
            }

            $viewPath = str_replace('.', '/', $path);
            $rootPath = str_replace('public', self::ROOT, $_SERVER['DOCUMENT_ROOT']);

            self::$viewFullPath = $rootPath . '/' . $viewPath . '.php';

            echo self::getContentsFromPath();
        } catch (MissingViewPath|InvalidViewPathException $e) {
            die($e->getMessage());
        } catch (\Throwable $e) {
            error_log($e->getTraceAsString());
        }

        return new static;
    }

    public static function redirect(string $path): self
    {
        header('Location: ' . $path);

        return new static;
    }

    public static function toRoute(string $routeName): self
    {
        self::redirect($GLOBALS[$routeName]);

        return new static;
    }

    private static function getContentsFromPath(): false|string
    {
        extract(self::$attributes);

        ob_start();

        include(self::$viewFullPath);

        $output = ob_get_contents();

        ob_end_clean();

        return $output;
    }
}