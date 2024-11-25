<?php
namespace App\Http\Middleware;
use App\User;
use App\Utilities\Utility;
use Closure;
use Redirect;
use Session;
use Auth;
use Log;


class NormalUserAuthenticated
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

            if (auth('web')->check()) {
                return $next($request);
            }
            else
            {
                return Redirect::guest('/users/login');
            }
    }
}
