<?php

declare(strict_types=1);

namespace GrigoryGerasimov\LaraLikeRouting\Core\Helpers;

final class Helper
{
    public static function clearString(string $str, string $option): string
    {
        try {
            $pattern = match ($option) {
                ClearStringOptions::SLASH->value => '/(^\/)|(\/$)/',
                ClearStringOptions::CURLY->value => '/(^{)|(}$)/',
                default => throw new \UnhandledMatchError('Invalid option provided')
            };
        } catch (\UnhandledMatchError $e) {
            die($e->getMessage());
        } catch (\Throwable $e) {
            error_log($e->getTraceAsString());
        }

        return preg_replace($pattern, '', $str);
    }
}