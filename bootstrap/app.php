<?php

// Comprehensive polyfill for environments where PHP ext-fileinfo is disabled (e.g. cPanel / shared hosting)
if (!defined('FILEINFO_MIME_TYPE')) {
    define('FILEINFO_MIME_TYPE', 16);
}

if (!class_exists('finfo', false)) {
    class finfo
    {
        public function __construct(int $flags = 0, ?string $magicFile = null) {}

        public function buffer(string $string, int $flags = 0, $context = null): string|false
        {
            if (str_starts_with($string, '%PDF-')) {
                return 'application/pdf';
            }
            return false;
        }

        public function file(string $filename, int $flags = 0, $context = null): string|false
        {
            if (is_file($filename) && is_readable($filename)) {
                $h = @fopen($filename, 'rb');
                if ($h) {
                    $head = fread($h, 5);
                    fclose($h);
                    if (str_starts_with($head, '%PDF-')) {
                        return 'application/pdf';
                    }
                }
            }
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            return match ($ext) {
                'pdf' => 'application/pdf',
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'webp' => 'image/webp',
                default => false,
            };
        }
    }
}

if (!function_exists('finfo_open')) {
    function finfo_open($flags = null, $magic = null) {
        return new \stdClass();
    }
}

if (!function_exists('finfo_file')) {
    function finfo_file($finfo, $filename, $flags = null, $context = null) {
        if (is_file($filename) && is_readable($filename)) {
            $h = @fopen($filename, 'rb');
            if ($h) {
                $head = fread($h, 5);
                fclose($h);
                if (str_starts_with($head, '%PDF-')) {
                    return 'application/pdf';
                }
            }
        }
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return match ($ext) {
            'pdf' => 'application/pdf',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            default => false,
        };
    }
}

if (!function_exists('finfo_buffer')) {
    function finfo_buffer($finfo, $string, $flags = null, $context = null) {
        if (str_starts_with($string, '%PDF-')) {
            return 'application/pdf';
        }
        return false;
    }
}

if (!function_exists('finfo_close')) {
    function finfo_close($finfo) {
        return true;
    }
}

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'The uploaded file exceeds the server upload limit (maximum 50 MB allowed).',
                ], 413);
            }
            return back()->withErrors(['pdf_file' => 'The uploaded file exceeds the upload limit (maximum 50 MB allowed).']);
        });
    })->create();
