<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Configure token expiration if needed
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
    }
}







// public function boot()
//     {
//         $this->registerPolicies();

//         if (!$this->app->routesAreCached()) {
//             Passport::routes(function ($router) {
//                 Route::group(['middleware' => 'api'], function ($router) {
//                     $router->get('/oauth/tokens', [
//                         'as' => 'passport.tokens.index',
//                         'uses' => '\\Laravel\\Passport\\Http\\Controllers\\AuthorizedAccessTokenController@forUser',
//                     ]);
//                     $router->delete('/oauth/tokens/{token_id}', [
//                         'as' => 'passport.tokens.destroy',
//                         'uses' => '\\Laravel\\Passport\\Http\\Controllers\\AuthorizedAccessTokenController@destroy',
//                     ]);
//                 });
//             });
//         }
//     }