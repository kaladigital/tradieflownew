<?php

namespace App\Http\Middleware;
use App\Models\UserSubscription;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class ActiveSubscriptionCheck extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $auth_user = request()->user();
        $has_active_subscription = UserSubscription::where('user_id','=',$auth_user->user_id)
            ->where('type','=','tradieflow')
            ->where('active','=','1')
            ->count();

        if (!$has_active_subscription) {
            return redirect('settings/subscriptions');
        }

        return $next($request);
    }
}
