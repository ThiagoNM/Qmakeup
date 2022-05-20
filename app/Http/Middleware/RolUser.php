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
     * @param  int  $id_rol
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $id_rol)
    {
        if ($request->user()->id_rol != $id_rol) {
            $url = $request->url();
            return redirect('/')
                ->with('error', "Access denied to {$url}");
        }

        return $next($request);
    }
}
