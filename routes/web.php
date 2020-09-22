<?php
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register web routes for your application. These
 * | routes are loaded by the RouteServiceProvider within a group which
 * | contains the "web" middleware group. Now create something great!
 * |
 */

Route::get('/', function () {
    return view('welcome');
});

Auth::routes([ 
    // 'verify' => true
]);

Route::get('/admin', 'AdminController@index')->middleware(['auth', 'auth.user.server'])->name('admin');

Route::get('/home', function () {
    return view('welcome');
})->middleware('auth')->name('home');


