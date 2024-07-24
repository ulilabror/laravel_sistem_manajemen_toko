<?php

// use Illuminate\Http\Request;
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

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\RoleController;
use App\Http\Controllers\API\V1\FileController;
use App\Http\Controllers\API\V1\ProductController;
use App\Http\Controllers\API\V1\TransactionController;

// Route::get('products', ['middleware' => 'auth.role:admin,user', 'uses' => 'ProductController@index', 'as' => 'products']);
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});



Route::prefix('users')->middleware(['auth:api'])->group(function () {

    Route::middleware(['auth.role:Admin'])->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('{id}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('{id}', [UserController::class, 'update']);
        Route::delete('{id}', [UserController::class, 'destroy']);
    });

    Route::middleware(['auth.role:Admin,Manager'])->group(function () {
        Route::get('{id}/points', [UserController::class, 'points']);
        Route::get('{id}/files', [UserController::class, 'files']);
        Route::get('{id}/transactions', [UserController::class, 'transactions']);
    });
});



Route::prefix('roles')->group(function () {
    Route::middleware(['auth.role:Admin']);
    Route::get('/', [RoleController::class, 'index']);
    Route::post('/', [RoleController::class, 'store']);
    Route::get('{id}', [RoleController::class, 'show']);
    Route::put('{id}', [RoleController::class, 'update']);
    Route::delete('{id}', [RoleController::class, 'destroy']);
});


Route::prefix('files')->middleware(['auth:api'])->group(function () {
    Route::get('/', [FileController::class, 'index']);
    Route::post('/', [FileController::class, 'store']);
    Route::get('{id}', [FileController::class, 'show']);
    Route::delete('{id}', [FileController::class, 'destroy']);
    Route::put('{id}', [FileController::class, 'update']);
});

Route::prefix('products')->middleware(['auth:api'])->group(function () {

    Route::post('/', [ProductController::class, 'store']);
    Route::put('{id}', [ProductController::class, 'update']);
    Route::delete('{id}', [ProductController::class, 'destroy']);
    Route::get('/', [ProductController::class, 'index']);
    Route::get('{id}', [ProductController::class, 'show']);
});



Route::prefix('transactions')->middleware(['auth:api'])->group(function () {
    Route::post('/', [TransactionController::class, 'store']);
    Route::put('{id}', [TransactionController::class, 'update']);
    Route::delete('{id}', [TransactionController::class, 'destroy']);
    Route::get('/', [TransactionController::class, 'index']);
    Route::get('{id}', [TransactionController::class, 'show']);
});


// <?php

// use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\API\V1\AuthController;
// use App\Http\Controllers\API\V1\UserController;
// use App\Http\Controllers\API\V1\RoleController;

// Route::controller(AuthController::class)->group(function () {
//     Route::post('login', 'login');
//     Route::post('register', 'register');
//     Route::post('logout', 'logout');
//     Route::post('refresh', 'refresh');
// });

// Route::prefix('users')->middleware(['auth:api'])->group(function () {
//     Route::get('/', [UserController::class, 'index'])->middleware('auth.role:view_users');
//     Route::get('{id}', [UserController::class, 'show'])->middleware('auth.role:view_user_details');
//     Route::post('/', [UserController::class, 'store'])->middleware('auth.role:create_users');
//     Route::put('{id}', [UserController::class, 'update'])->middleware('auth.role:edit_users');
//     Route::delete('{id}', [UserController::class, 'destroy'])->middleware('auth.role:delete_users');

//     Route::get('{id}/points', [UserController::class, 'points'])->middleware('auth.role:view_user_points');
//     Route::get('{id}/files', [UserController::class, 'files'])->middleware('auth.role:view_user_files');
//     Route::get('{id}/transactions', [UserController::class, 'transactions'])->middleware('auth.role:view_user_transactions');
// });

// Route::prefix('roles')->middleware(['auth:api'])->group(function () {
//     Route::get('/', [RoleController::class, 'index'])->middleware('auth.role:view_roles');
//     Route::post('/', [RoleController::class, 'store'])->middleware('auth.role:create_roles');
//     Route::get('{id}', [RoleController::class, 'show'])->middleware('auth.role:view_role_details');
//     Route::put('{id}', [RoleController::class, 'update'])->middleware('auth.role:edit_roles');
//     Route::delete('{id}', [RoleController::class, 'destroy'])->middleware('auth.role:delete_roles');
// });
