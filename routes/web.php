<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('', 'AppController@app')->name('app');
    Route::get('profile', 'ProfileController@edit')->name('profile.edit');
    Route::post('profile', 'ProfileController@update')->name('profile:update');

    Route::group(['prefix' => 'ajax'], function () {
        Route::resource('flavours', 'FlavourController', [
            'only' => ['index', 'show', 'store']
        ]);

        Route::resource('liquids', 'LiquidController', [
            'only' => ['index', 'show', 'store', 'update', 'destroy']
        ]);
        Route::post('liquids/{liquid}/new-version', 'LiquidController@newVersion');

        Route::get('liquids/{liquid}/comments', 'CommentController@index');
        Route::post('liquids/{liquid}/comments', 'CommentController@store');
        Route::delete('liquids/{liquid}/comments/{comment}', 'CommentController@destroy');

        Route::resource('vendors', 'VendorController', [
            'only' => ['index', 'show']
        ]);
    });

    Route::group([
        'namespace' => 'Admin',
        'middleware' => 'requires-admin',
        'prefix' => 'admin'
    ], function () {
        Route::name('admin.')->group(function () {
            Route::get('', function () {
                return view('admin.index');
            })->name('index');

            Route::resource('vendors', 'VendorController');
            Route::resource('flavours', 'FlavourController');
            Route::resource('users', 'UserController');

            Route::get('statistics/flavours', 'StatisticsController@flavourStats')
                ->name('stats.flavours');
            Route::get('statistics/liquids-per-day', 'StatisticsController@liquidsPerDay')
                ->name('stats.liquids-per-day');
        });
    });
});

$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->get('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
//$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//$this->post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');

