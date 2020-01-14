<?php

namespace App\Http\Middleware;

use Closure;

class CheckRequestHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

       // if($request->hasHeader('Accept') && $request->header('Accept') === 'application/json'){
            return $next($request);
        //  }

        return abort(403, 'application/json is required in header to process in (Accept) key.');

    }
}
