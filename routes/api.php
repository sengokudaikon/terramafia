<?php

use App\Http\Controllers\Api\V1\User\SocialAuthController;
use App\Http\Controllers\Api\V1\User\UserAuthController;
use App\Http\Controllers\Api\V1\User\UserController;
use App\Http\Controllers\IndexController;
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
    Route::get('info', [IndexController::class, 'info'])->name('api.v1.info');

    // User
    Route::group(['namespace' => 'User'], function ()
    {
        // Auth
        Route::group(
            ['prefix' => 'auth'],
            function ()
            {
                Route::post('login', [UserAuthController::class, 'login'])
                    ->name('api.v1.auth.login');
                Route::post('logout', [UserAuthController::class, 'logout'])
                    ->name('api.v1.auth.logout')
                    ->middleware('auth');
                Route::post('forgot', [UserAuthController::class, 'forgot'])
                    ->name('api.v1.auth.forgot');
                Route::post('resetPassword', [UserAuthController::class, 'resetPassword'])
                    ->name('api.v1.auth.resetPassword');

                Route::get('login/{provider}', [SocialAuthController::class, 'getAuthUrlForExternalService'])
                    ->name('api.v1.auth.login.social');
                Route::post('login/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])
                    ->name('api.v1.auth.login.social.callback');
            }
        );

        //User
        Route::group(
            ['prefix' => 'users'],
            function(){
                Route::get('/', [UserController::class, 'getPlayerList'])->name('api.v1.users.list');
                Route::post('register', [UserController::class, 'registerPlayer'])
                    ->name('api.v1.users.register');
                Route::get('{uuid}', [UserController::class, 'getByUuid'])->name('api.v1.users.get');
                Route::delete('{uuid}', [UserController::class, 'deletePlayer'])->name('api.v1.users.delete');
            }
        );

        // Account
        Route::group(['prefix' => 'users/me'], function () {
            Route::group(['middleware' => 'auth'], function () {
                Route::get('/', [UserController::class, 'getMe'])->name('api.v1.user.me.get');
                Route::put('/', [UserController::class, 'updatePlayer'])->name('api.v1.user.me.update');
                Route::post('/', [UserController::class, 'addPersonalInfo'])->name('api.v1.user.me.add');

                Route::post('password', [UserController::class, 'changePassword'])
                    ->name('api.v1.users.me.password.change');
                Route::put('email', [UserController::class, 'changeEmail'])
                    ->name('api.v1.users.me.email.change');
            });
        });

        Route::post('users/me/email/confirmation', [UserController::class, 'confirmEmail'])
            ->name('api.v1.users.me.email.confirmation');
    });
});
