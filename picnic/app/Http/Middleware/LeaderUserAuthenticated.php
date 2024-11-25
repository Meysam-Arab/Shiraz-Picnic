<?php
namespace App\Http\Middleware;
use App\User;
use App\Utilities\Utility;
use Closure;
use Redirect;
use Session;
use Auth;


class LeaderUserAuthenticated
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
            if (auth('web')->check()&&((auth('web')->user()->type == User::TypeLeader)||
                    (auth('web')->user()->type == User::TypeOwner)||
                    (auth('web')->user()->type == User::TypeOperator)||
                    (auth('web')->user()->type == User::TypeAdmin))) {
                return $next($request);
            }
            else
            {
                return abort(401, 'Unauthorized');
            }
    }
}
