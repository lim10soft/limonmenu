<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/panel');
});

// Admin panel — /{slug} çakışmasını önlemek için ÖNCE tanımla
Route::get('/panel/{any?}', function () {
    return response(view('admin'))
        ->header('X-Frame-Options', 'ALLOWALL')
        ->header('Content-Security-Policy', "frame-ancestors *");
})->where('any', '.*');

// Müşteri QR menü SPA
Route::get('/{slug}', function () {
    return view('customer');
})->where(['slug' => '[a-z0-9\-]+']);

Route::get('/{slug}/dept/{deptToken}', function () {
    return view('customer');
})->where(['slug' => '[a-z0-9\-]+', 'deptToken' => '[a-zA-Z0-9]+']);

Route::get('/{slug}/masa/{token}', function () {
    return view('customer');
})->where(['slug' => '[a-z0-9\-]+', 'token' => '[a-zA-Z0-9]+']);

Route::get('/{slug}/masa/{token}/siparis/{orderId}', function () {
    return view('customer');
})->where(['slug' => '[a-z0-9\-]+', 'token' => '[a-zA-Z0-9]+']);
