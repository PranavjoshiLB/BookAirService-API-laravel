<?php

namespace App\Providers;

use App\Model\Account\UserSession;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function (Request $request) {
            if ($request->hasHeader('x-session-id')) {
                $session_id = $request->header('x-session-id');
                $usersession = UserSession::where('session_id', $session_id)->first();
                if (!is_null($usersession)) {
                    $user_id = $usersession->userid;
                    return \App\Model\Account\User::find($user_id);
                }

            }
        });
    }
}
