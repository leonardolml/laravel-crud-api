<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

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

Route::prefix('v1')->name('v1.')->group(function () {
    Route::prefix('items')->name('items.')->group(function () {
        // TODO Add bulk create, find, update, delete and restore operations
        Route::prefix('bulk')->name('bulk.')->group(function () {
            Route::get('{ids}', [ ItemController::class, 'bulkFind' ])->name('find');
            Route::post('', [ ItemController::class, 'bulkCreate' ])->name('reate');
            Route::patch('bulk', [ ItemController::class, 'bulkUpdate' ])->name('update');
        });
        

        // Admin single operations (TODO requires authentication)
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('all', [ ItemController::class, 'adminAll' ])->name('all');
            Route::get('find/{id}', [ ItemController::class, 'adminFind' ])->name('find');
            // Route::post('', [ ItemController::class, 'adminCreate' ])->name('create');
            Route::patch('{id}', [ ItemController::class, 'adminUpdate' ])->name('update');
            Route::delete('{id}', [ ItemController::class, 'adminDelete' ])->name('delete');
            Route::patch('restore/{id}', [ ItemController::class, 'adminRestore' ])->name('restore');

            // Admin bulk operations
            // TODO Add bulk create, find, update, delete, restore
            // Route::patch('all', [ ItemController::class, 'adminUpdateAll' ])->name('updateAll');
            Route::delete('all', [ ItemController::class, 'adminDeleteAll' ])->name('deleteAll');
            // Route::patch('all', [ ItemController::class, 'restoreAll' ]);
            // TODO Add global create, find, update, delete and restore operations
        });

        // User single operations
        Route::get('', [ ItemController::class, 'all' ])->name('all');
        Route::get('{id}', [ ItemController::class, 'find' ])->name('find');
        Route::post('', [ ItemController::class, 'create' ])->name('create');
        Route::patch('{id}', [ ItemController::class, 'update' ])->name('update');
        Route::delete('{id}', [ ItemController::class, 'delete' ])->name('delete');
        Route::patch('restore/{id}', [ ItemController::class, 'restore' ])->name('restore');
        
    });
    
});
