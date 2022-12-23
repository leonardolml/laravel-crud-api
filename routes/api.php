<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemApiController;

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
            Route::get('{ids}', [ ItemApiController::class, 'bulkFind' ])->name('find');
            Route::post('', [ ItemApiController::class, 'bulkCreate' ])->name('reate');
            Route::patch('bulk', [ ItemApiController::class, 'bulkUpdate' ])->name('update');
        });
        

        // Admin single operations (TODO requires authentication)
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('all', [ ItemApiController::class, 'adminAll' ])->name('all');
            Route::get('find/{id}', [ ItemApiController::class, 'adminFind' ])->name('find');
            // Route::post('', [ ItemApiController::class, 'adminCreate' ])->name('create');
            Route::patch('{id}', [ ItemApiController::class, 'adminUpdate' ])->name('update');
            Route::delete('{id}', [ ItemApiController::class, 'adminDelete' ])->name('delete');
            Route::patch('restore/{id}', [ ItemApiController::class, 'adminRestore' ])->name('restore');

            // Admin bulk operations
            // TODO Add bulk create, find, update, delete, restore
            // Route::patch('all', [ ItemApiController::class, 'adminUpdateAll' ])->name('updateAll');
            Route::delete('all', [ ItemApiController::class, 'adminDeleteAll' ])->name('deleteAll');
            // Route::patch('all', [ ItemApiController::class, 'restoreAll' ]);
            // TODO Add global create, find, update, delete and restore operations
        });

        // User single operations
        Route::get('', [ ItemApiController::class, 'all' ])->name('all');
        Route::get('{id}', [ ItemApiController::class, 'find' ])->name('find');
        Route::post('', [ ItemApiController::class, 'create' ])->name('create');
        Route::patch('{id}', [ ItemApiController::class, 'update' ])->name('update');
        Route::delete('{id}', [ ItemApiController::class, 'delete' ])->name('delete');
        Route::patch('restore/{id}', [ ItemApiController::class, 'restore' ])->name('restore');
        
    });
    
});
