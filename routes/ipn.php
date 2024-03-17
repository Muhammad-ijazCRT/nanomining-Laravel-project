<?php

use Illuminate\Support\Facades\Route;

Route::post('paypal', 'Paypal\ProcessController@ipn')->name('Paypal');

    // All adminn rotues are deleted by mijaz, you can contact me to create something interesting: https://www.fiverr.com/dowhfcrt or whatsapp: +92 312 9496281
