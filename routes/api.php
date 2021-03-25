<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['namespace' => 'Api\V1', 'middleware' => 'api', 'prefix' => 'v1'], function () {

    // Test
    Route::get('info', 'InfoController@index')->name('api.v1.info');

    // Users
    Route::group(['namespace' => 'Users'], function ()
    {
        // Auth
        Route::group(
            ['prefix' => 'auth'],
            function ()
            {
                Route::post('login', 'UserAuthController@login')
                    ->name('api.v1.auth.login');
                Route::post('logout', 'UserAuthController@logout')
                    ->name('api.v1.auth.logout')
                    ->middleware('auth');
                Route::post('forgot', 'UserAuthController@forgot')
                    ->name('api.v1.auth.forgot');
                Route::post('resetPassword', 'UserAuthController@resetPassword')
                    ->name('api.v1.auth.resetPassword');

                Route::get('login/{provider}', 'SocialAuthController@getAuthUrlForExternalService')
                    ->name('api.v1.auth.login.social');
                Route::post('login/{provider}/callback', 'SocialAuthController@handleProviderCallback')
                    ->name('api.v1.auth.login.social.callback');
            }
        );

        //User
        Route::group(
            ['prefix' => 'users'],
            function(){
                Route::get('/', 'UserController@getPlayerList')->name('api.v1.users.list');
                Route::post('register', 'UserController@registerPlayer')
                    ->name('api.v1.users.register');
                Route::get('{uuid}', 'UserController@getByUuid')->name('api.v1.users.get');
                Route::delete('{uuid}', 'UserController@deletePlayer')->name('api.v1.users.delete');
            }
        );

        // Account
        Route::group(['prefix' => 'users/me'], function () {
            Route::group(['middleware' => 'auth'], function () {
                Route::get('/', 'UserController@getMe')->name('api.v1.user.me.get');
                Route::put('/', 'UserController@updatePlayer')->name('api.v1.user.me.update');
                Route::post('/', 'UserController@addPersonalInfo')->name('api.v1.user.me.add');

                Route::post('password', 'UserController@changePassword')
                    ->name('api.v1.users.me.password.change');
                Route::put('email', 'UserController@changeEmail')
                    ->name('api.v1.users.me.email.change');
            });
        });

        Route::post('users/me/email/confirmation', 'UserController@confirmEmail')
            ->name('api.v1.users.me.email.confirmation');
    });
});
