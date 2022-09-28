<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemWebController;
use App\Http\Controllers\ProfileWebController;

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
Route::prefix('items')->name('items.')->group(function () {
    Route::get('', [ ItemWebController::class, 'all' ])->name('all');
    // Route::get('{id}', [ ItemWebController::class, 'find' ])->name('find');
});

Route::prefix('profiles')->name('profiles.')->group(function () {
    Route::get('', [ ProfileWebController::class, 'index' ])->name('index');
});
