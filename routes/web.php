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
    Route::get('profile', 'ProfileController@edit')->name('profile:edit');
    Route::post('profile', 'ProfileController@update')->name('profile:update');

    Route::group(['prefix' => 'ajax'], function () {
        Route::resource('flavours', 'FlavourController', [
            'only' => ['index', 'show', 'store', 'update', 'delete']
        ]);

        Route::resource('liquids', 'LiquidController', [
            'only' => ['index', 'show', 'store', 'update', 'delete']
        ]);

        Route::resource('vendors', 'VendorController', [
            'only' => ['index', 'show', 'store', 'update', 'delete']
        ]);
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

