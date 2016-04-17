<?php

namespace Inoplate\Account\Http\Middleware;

use Closure;
use Authis;

class Authorize
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure                   $next
     * @param  string|null               $ablity
     * @return mixed
     */
    public function handle($request, Closure $next, $ability = null)
    {
        // Naming convention of ability
        // Taken from route name
        $ability = $ability ?: $request->route()->getName();

        if( !Authis::check($ability)) {
            if ($request->ajax()) {
                return response('Unauthorized.', 403);
            } else {
                return back()->with([
                                'error' => trans('inoplate-account::messages.auth.unauthorized',
                                    ['url' => $request->url() ])
                                ]);
            }
        }

        return $next($request);
    }
}