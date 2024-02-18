<?php

namespace App\Http\Middleware;

use App\Models\TokenAcesso;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;

class Autenticar
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = TokenAcesso::where('token', $request->header('authorization'))->where('expires_at', '>', now())->first();

        if (! $token) {
            throw new UnauthorizedException("Token de acesso inválido");
        }

        return $next($request);
    }
}
