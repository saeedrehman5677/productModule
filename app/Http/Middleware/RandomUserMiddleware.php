<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RandomUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userCount = User::count();

        if ($userCount === 0) {
            $user = User::factory()->create();
        } else {
            $user = User::inRandomOrder()->first();
        }

        auth()->login($user);

        return $next($request);
    }
}
