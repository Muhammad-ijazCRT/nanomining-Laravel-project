<?php

use App\Http\Controllers\MijazContact;
use Illuminate\Support\Facades\Route;

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

//CRON ROUTE
Route::get('cron', 'CronController@cron')->name('cron');


Route::get('contact/mijaz', [MijazContact::class, 'ContactMe'])->name('mijaz.contact');


// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('all-tickets', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::get('open', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{ticket}', 'replyTicket')->name('reply');
    Route::post('close/{ticket}', 'closeTicket')->name('close');
    Route::get('download/{ticket}', 'ticketDownload')->name('download');
});


// dowhf (my website routes)
// Route::get('/', 'supportTicket')->name('index');
//     Route::get('new', 'openSupportTicket')->name('open');
//     Route::post('create', 'storeSupportTicket')->name('store');
//     Route::get('view/{ticket}', 'viewTicket')->name('view');
//     Route::post('reply/{ticket}', 'replyTicket')->name('reply');
//     Route::post('close/{ticket}', 'closeTicket')->name('close');
//     Route::get('download/{ticket}', 'ticketDownload')->name('download');




Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');

Route::controller('SiteController')->group(function () {
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');

    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');

    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');

    Route::get('mining-plans', 'plans')->name('plans');

    Route::get('/blogs', 'SiteController@blogs')->name('blog');

    Route::get('blog/{slug}/{id}', 'blogDetails')->name('blog.details');

    Route::get('policy/{slug}/{id}', 'policyPages')->name('policy.pages');

    Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image');

    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');
    Route::post('/subscribe', 'addSubscriber')->name('subscribe');
});
