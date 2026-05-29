<?php

use App\Http\Controllers\Api\Admin\AdminTenantController;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\DepartmentController;
use App\Http\Controllers\Api\Admin\LanguageController;
use App\Http\Controllers\Api\Admin\OrderController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\ReviewController;
use App\Http\Controllers\Api\Admin\SettingsController;
use App\Http\Controllers\Api\Admin\TableController;
use App\Http\Controllers\Api\Admin\TranslateController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\GitDeployController;
use App\Http\Controllers\Api\Admin\SyncController;
use App\Http\Controllers\Api\Admin\ImageUploadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| NexoPOS Webhook (public — token auth kontrolü controller'da)
|--------------------------------------------------------------------------
*/
Route::post('/nexopos/webhook/product', [WebhookController::class, 'nexoposProduct']);

/*
|--------------------------------------------------------------------------
| Müşteri QR Menü API (public)
|--------------------------------------------------------------------------
*/
Route::prefix('menu/{slug}')->middleware('tenant')->group(function () {
    Route::get('/',                        [MenuController::class, 'show']);
    Route::get('/dept/{deptToken}',        [MenuController::class, 'show'])->defaults('tableToken', null);
    Route::get('/{tableToken}',            [MenuController::class, 'show']);
    Route::post('/order',                  [MenuController::class, 'order']);
    Route::post('/review',                 [MenuController::class, 'review']);
});

/*
|--------------------------------------------------------------------------
| Admin API
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);

        Route::get('/dashboard', [DashboardController::class, 'index']);

        Route::apiResource('tables', TableController::class)->only(['index', 'store', 'destroy']);
        Route::patch('tables/{id}', [TableController::class, 'update']);

        Route::post('translate/all', [TranslateController::class, 'autoTranslateAll']);
        Route::post('categories/auto-translate', [TranslateController::class, 'autoTranslateAll']);
        Route::apiResource('categories', CategoryController::class)->only(['index', 'store', 'destroy']);
        Route::put('categories/{id}', [CategoryController::class, 'update']);
        Route::patch('categories/{id}', [CategoryController::class, 'update']);

        Route::post('products/auto-translate', [TranslateController::class, 'autoTranslateAll']);
        Route::apiResource('products', ProductController::class)->only(['index', 'store', 'destroy']);
        Route::put('products/{id}', [ProductController::class, 'update']);
        Route::patch('products/{id}', [ProductController::class, 'update']);

        Route::get('orders', [OrderController::class, 'index']);
        Route::patch('orders/{id}', [OrderController::class, 'update']);

        Route::get('languages', [LanguageController::class, 'index']);
        Route::put('languages', [LanguageController::class, 'update']);

        Route::put('settings', [SettingsController::class, 'update']);
        Route::post('nexopos/sync', [SettingsController::class, 'syncNexopos']);

        Route::get('departments', [DepartmentController::class, 'index']);
        Route::post('departments', [DepartmentController::class, 'store']);
        Route::patch('departments/{id}', [DepartmentController::class, 'update']);
        Route::delete('departments/{id}', [DepartmentController::class, 'destroy']);
        Route::get('departments/{id}/products', [DepartmentController::class, 'getProducts']);
        Route::put('departments/{id}/products', [DepartmentController::class, 'updateProducts']);
        Route::get('departments/{id}/categories', [DepartmentController::class, 'getCategories']);
        Route::put('departments/{id}/categories', [DepartmentController::class, 'updateCategories']);

        Route::get('reviews', [ReviewController::class, 'index']);
        Route::get('reviews/export', [ReviewController::class, 'export']);
        Route::delete('reviews/{id}', [ReviewController::class, 'destroy']);

        Route::post('upload-image', [ImageUploadController::class, 'upload']);

        Route::post('sync/receive', [SyncController::class, 'receive']);
        Route::post('sync/upload-image/{nexoposId}', [SyncController::class, 'uploadImage']);

        Route::post('git/pull', [GitDeployController::class, 'pull']);

        // Superadmin-only
        Route::middleware('admin')->group(function () {
            Route::get('tenants', [AdminTenantController::class, 'index']);
            Route::patch('tenants/{id}', [AdminTenantController::class, 'update']);
            Route::delete('tenants/{id}', [AdminTenantController::class, 'destroy']);
            Route::get('git/config', [GitDeployController::class, 'getConfig']);
            Route::post('git/config', [GitDeployController::class, 'saveConfig']);
            Route::post('git/migrate', [GitDeployController::class, 'migrate']);
        });
    });
});

// GitHub Webhook — CSRF hariç
Route::post('/git/webhook', [GitDeployController::class, 'webhook']);
