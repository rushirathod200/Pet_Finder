<?php

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'login' => \App\Http\Middleware\LoginMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $isDatabaseConnectionIssue = static function (Throwable $exception) use (&$isDatabaseConnectionIssue): bool {
            $message = strtolower($exception->getMessage());
            $code = (string) $exception->getCode();

            $isConnectionMessage = str_contains($message, 'sqlstate[hy000] [2002]')
                || str_contains($message, 'connection refused')
                || str_contains($message, 'operation timed out')
                || str_contains($message, 'no such host')
                || str_contains($message, 'server has gone away')
                || str_contains($message, 'could not find driver');

            $isConnectionCode = in_array($code, ['2002', '2006', '1045'], true);

            if (($exception instanceof QueryException || $exception instanceof PDOException) && ($isConnectionMessage || $isConnectionCode)) {
                return true;
            }

            $previous = $exception->getPrevious();
            return $previous instanceof Throwable ? $isDatabaseConnectionIssue($previous) : false;
        };

        $exceptions->render(function (Throwable $exception, Request $request) use ($isDatabaseConnectionIssue) {
            if (!$isDatabaseConnectionIssue($exception)) {
                return null;
            }

            $message = 'Database is temporarily unavailable. Please try again in a few minutes.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 503);
            }

            if ($request->isMethod('POST') && $request->is('login')) {
                return redirect()
                    ->back()
                    ->withInput($request->except(['login_password', 'signup_password', 'signup_password_confirmation']))
                    ->withErrors(['identifier' => $message]);
            }

            return response()->view('errors.database-unavailable', [
                'message' => $message,
            ], 503);
        });
    })->create();
