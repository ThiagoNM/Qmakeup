<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RolUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  int  $ir_rol
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $ir_rol)
    {
        if ($request->user()->ir_rol != $ir_rol) {
            $url = $request->url();
            return redirect('/')
                ->with('error', "Access denied to {$url}");
        }

        return $next($request);
    }
}
