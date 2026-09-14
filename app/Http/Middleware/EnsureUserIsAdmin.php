<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || (int) $request->user()->role !== 1) {
            abort(403, 'このページにアクセスする権限がありません。');
        }

        return $next($request);
    }
}