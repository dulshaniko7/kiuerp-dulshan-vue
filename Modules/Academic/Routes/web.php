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


//Route::middleware(["auth.admin:admin", "admin.permissions:admin"])->group(function() {

    Route::get('/admin/dashboard','AcademicController@dashboard')->name('admin.dashboard');

    Route::get('/academic/faculty', 'FacultyController@index')->name('faculty.list');
    Route::post('/academic/faculty', 'FacultyController@index')->name('faculty.fetch');
    Route::get('/academic/faculty/trash', 'FacultyController@trash')->name('faculty.list.trash');
    Route::post('/academic/faculty/trash', 'FacultyController@trash')->name('faculty.list.trash');
    Route::get('/academic/faculty/create', 'FacultyController@create')->name('faculty.add');
    Route::post('/academic/faculty/create', 'FacultyController@store')->name('faculty.store');
    Route::get('/academic/faculty/edit/{id}', 'FacultyController@edit')->name('faculty.edit');
    Route::post('/academic/faculty/edit/{id}', 'FacultyController@update')->name('faculty.update');
    Route::post('/academic/faculty/delete/{id}', 'FacultyController@destroy')->name('faculty.delete');
    Route::post('/academic/faculty/restore/{id}', 'FacultyController@restore')->name('faculty.restore');
    Route::post('/academic/faculty/searchData', 'FacultyController@searchData')->name('faculty.search.data');

    Route::get('/academic/department', 'DepartmentController@index')->name('department.list');
    Route::post('/academic/department', 'DepartmentController@index')->name('department.fetch');
    Route::get('/academic/department/trash', 'DepartmentController@trash')->name('department.list.trash');
    Route::post('/academic/department/trash', 'DepartmentController@trash')->name('department.list.fetch');
    Route::get('/academic/department/create', 'DepartmentController@create')->name('department.add');
    Route::post('/academic/department/create', 'DepartmentController@store')->name('department.store');
    Route::get('/academic/department/edit/{id}', 'DepartmentController@edit')->name('department.edit');
    Route::post('/academic/department/edit/{id}', 'DepartmentController@update')->name('department.update');
    Route::post('/academic/department/delete/{id}', 'DepartmentController@destroy')->name('department.delete');
    Route::post('/academic/department/restore/{id}', 'DepartmentController@restore')->name('department.restore');
    Route::post('/academic/department/searchData', 'DepartmentController@searchData')->name('department.search.data');

//});

