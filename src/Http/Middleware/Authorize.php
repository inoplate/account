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
    public function handle($request, Closure $next, $model = null, $ability = null)
    {
        // Naming convention of ability
        // Taken from route name
        $ability = $ability ?: $request->route()->getName();
        $model = $model ? $request->route($model) : null;

        $check = $model ? Authis::forResource($model)->check($ability) : Authis::check($ability);

        if( !$check) {
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