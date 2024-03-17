<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Auth')->group(function () {
    Route::controller('LoginController')->group(function () {
        Route::get('/', 'showLoginForm')->name('login');
        Route::post('/', 'login')->name('login');
        Route::get('logout', 'logout')->middleware('admin')->name('logout');
    });

    // Admin routes
    // All adminn rotues are deleted by mijaz, you can contact me to create something interesting: https://www.fiverr.com/dowhfcrt or whatsapp: +92 312 9496281
});
