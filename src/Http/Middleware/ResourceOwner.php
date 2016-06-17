<?php

namespace Inoplate\Account\Http\Middleware;

use Closure;
use Roseffendi\Authis\Authis;

class ResourceOwner
{
    protected $authis;

    public function __construct(Authis $authis)
    {
        $this->authis = $authis;
    }

    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure                   $next
     * @param  string|null               $ablity
     * @return mixed
     */
    public function handle($request, Closure $next, $resourceParamName = 'id')
    {
        $resource = $request->{$resourceParamName};

        
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