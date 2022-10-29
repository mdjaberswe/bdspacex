<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('rocket.launch');
});

Route::get('rocket-launch', [App\Http\Controllers\RocketController::class, 'launch'])->name('rocket.launch');
Route::post('rocket-estimate-time', [App\Http\Controllers\RocketController::class, 'estimateTime'])->name('rocket.est');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
