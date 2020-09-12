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

Route::prefix('dashboard')->name('dashboard.')->group(function() {

    Route::namespace('Auth')->group(function () {

        //Login Routes
        Route::get('/login', 'AdminLoginController@showLoginForm')->name('login');
        Route::post('/login', 'AdminLoginController@login');
        Route::get('/logout', 'AdminLoginController@logout')->name('logout');

        //Forgot Password Routes
        Route::get('/password/reset', 'AdminForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('/password/email', 'AdminForgotPasswordController@sendResetLinkEmail')->name('password.email');

        //Reset Password Routes
        Route::get('/password/reset/{token}', 'AdminResetPasswordController@showResetForm')->name('password.reset');
        Route::post('/password/reset', 'AdminResetPasswordController@reset')->name('password.update');
    });
});

Route::middleware(["auth.admin:admin", "admin.permissions:admin"])->group(function(){

    Route::get('/admin/dashboard','AdminController@index')->name('admin.dashboard');

    Route::get('/admin','AdminController@index')->name('admin.index');
    Route::post('/admin','AdminController@index')->name('admin.fetch');
    Route::get('/admin/create','AdminController@create')->name('admin.create');
    Route::post('/admin/create','AdminController@store')->name('admin.store');
    Route::get('/admin/view/{id}','AdminController@show')->name('admin.view');
    Route::get('/admin/edit/{id}','AdminController@edit')->name('admin.edit');
    Route::post('/admin/edit/{id}','AdminController@update')->name('admin.update');
    Route::post('/admin/delete/{id}','AdminController@destroy')->name('admin.delete');
    Route::post('/admin/restore/{id}','AdminController@restore')->name('admin.restore');
    Route::post('/admin/searchData','AdminController@searchData')->name('admin.search.data');

    Route::get('/admin_role','AdminRoleController@index')->name('admin_role.index');
    Route::post('/admin_role','AdminRoleController@index')->name('admin_role.fetch');
    Route::get('/admin_role/create','AdminRoleController@create')->name('admin_role.create');
    Route::post('/admin_role/create','AdminRoleController@store')->name('admin_role.store');
    Route::get('/admin_role/view/{id}','AdminRoleController@show')->name('admin_role.view');
    Route::get('/admin_role/edit/{id}','AdminRoleController@edit')->name('admin_role.edit');
    Route::post('/admin_role/edit/{id}','AdminRoleController@update')->name('admin_role.update');
    Route::post('/admin_role/delete/{id}','AdminRoleController@destroy')->name('admin_role.delete');
    Route::post('/admin_role/restore/{id}','AdminRoleController@restore')->name('admin_role.restore');
    Route::post('/admin_role/searchData','AdminRoleController@searchData')->name('admin_role.search.data');
});
