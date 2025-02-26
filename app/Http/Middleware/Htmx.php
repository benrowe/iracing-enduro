<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class Htmx
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response|IlluminateResponse $response */
        $response = $next($request);
        $target = $request->headers->get('hx-target');

        if ($target && $response instanceof IlluminateResponse && $response->original instanceof View) {
            $response->setContent($response->original->fragment($target));
        }
        return $response;
    }
}
