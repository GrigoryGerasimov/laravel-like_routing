<?php

declare(strict_types=1);

namespace GrigoryGerasimov\LaraLikeRouting\Core\View;

use GrigoryGerasimov\LaraLikeRouting\Core\Exceptions\View\{
    MissingViewPath,
    InvalidViewPathException
};

class View
{
    protected const ROOT = 'resources/views';
    protected static string $viewFullPath;

    public static function make(string $path): void
    {
        try {
            if (!isset($path)) {
                throw new MissingViewPath('No view path provided');
            }
            if (gettype($path) !== 'string') {
                throw new InvalidViewPathException('Invalid view path');
            }

            $viewPath = str_replace('.', '/', $path);
            $rootPath = str_replace('public', self::ROOT, $_SERVER['DOCUMENT_ROOT']);

            self::$viewFullPath = $rootPath . '/' . $viewPath . '.php';

            echo self::getContentsFromPath();
        } catch (MissingViewPath | InvalidViewPathException $e) {
            die($e->getMessage());
        } catch (\Throwable $e) {
            error_log($e->getTraceAsString());
        }
    }

    private static function getContentsFromPath(): false|string
    {
        ob_start();

        include(self::$viewFullPath);

        $output = ob_get_contents();

        ob_end_clean();

        return $output;
    }
}