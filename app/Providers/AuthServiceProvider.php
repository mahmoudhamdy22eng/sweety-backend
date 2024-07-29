<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
        // public function boot()
        // {
        //     $this->registerPolicies();

        //     Passport::routes();
        //     // Additional configurations if needed
        // }


        public function boot()
    {
        $this->registerPolicies();

        if (!$this->app->routesAreCached()) {
            Passport::routes(function ($router) {
                Route::group(['middleware' => 'api'], function ($router) {
                    $router->get('/oauth/tokens', [
                        'as' => 'passport.tokens.index',
                        'uses' => '\\Laravel\\Passport\\Http\\Controllers\\AuthorizedAccessTokenController@forUser',
                    ]);
                    $router->delete('/oauth/tokens/{token_id}', [
                        'as' => 'passport.tokens.destroy',
                        'uses' => '\\Laravel\\Passport\\Http\\Controllers\\AuthorizedAccessTokenController@destroy',
                    ]);
                });
            });
        }
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