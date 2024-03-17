<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;


Route::namespace('User\Auth')->name('user.')->group(function () {

    Route::controller('LoginController')->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::get('logout', 'logout')->middleware('auth')->name('logout');
    });

    Route::controller('RegisterController')->group(function () {
        Route::get('register', 'showRegistrationForm')->name('register');
        Route::post('register', 'register')->middleware('registration.status');
        Route::post('check-mail', 'checkUser')->name('checkUser');
    });

    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('reset', 'showLinkRequestForm')->name('request');
        Route::post('email', 'sendResetCodeEmail')->name('email');
        Route::get('code-verify', 'codeVerify')->name('code.verify');
        Route::post('verify-code', 'verifyCode')->name('verify.code');
    });

    Route::controller('ResetPasswordController')->group(function () {
        Route::post('password/reset', 'reset')->name('password.update');
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
    });
});

Route::middleware('auth')->name('user.')->group(function () {
    //authorization
    Route::namespace('User')->controller('AuthorizationController')->group(function () {
        Route::get('authorization', 'authorizeForm')->name('authorization');
        Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'emailVerification')->name('verify.email');
        Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
        Route::post('verify-g2fa', 'g2faVerification')->name('go2fa.verify');
    });

    Route::middleware(['check.status'])->group(function () {

        Route::get('user-data', 'User\UserController@userData')->name('data');
        Route::post('user-data-submit', 'User\UserController@userDataSubmit')->name('data.submit');

        Route::middleware('registration.complete')->namespace('User')->group(function () {

            Route::controller('UserController')->group(function () {
                Route::get('dashboard', 'home')->name('home');
                Route::get('testcorn', 'TestCorn')->name('testcorn');

                Route::get('get-mining-machine', 'getMiningMachine')->name('getMiningMachine');

                Route::get('get-mining-server-data', 'getMiningServer')->name('get.mining.server.data');
                Route::get('single-mining-server-data/{id?}', 'singleMiningServer')->name('single.mining.server.data');
                Route::post('user-trc20-address', 'storeUserTrc20Address')->name('user-trc20-address');

                Route::get('/new-cron',  'checkCronJib')->name('check-cron-job');

                //2FA
                Route::get('twofactor', 'show2faForm')->name('twofactor');
                Route::post('twofactor/enable', 'create2fa')->name('twofactor.enable');
                Route::post('twofactor/disable', 'disable2fa')->name('twofactor.disable');

                //KYC
                Route::get('kyc-form', 'kycForm')->name('kyc.form');
                Route::get('kyc-data', 'kycData')->name('kyc.data');
                Route::post('kyc-submit', 'kycSubmit')->name('kyc.submit');

                //Report
                Route::any('payment-log', 'paymentHistory')->name('payment.history');

                // transactions
                Route::get('transactions', 'transactions')->name('transactions');
                Route::get('transactions/current-week', 'CurrentWeekTransaction')->name('current_week_transactions');
                Route::get('transactions/current-month', 'CurrentMonthTransaction')->name('current_month_transactions');


                Route::get('/all-transactions', 'AllTransaction')->name('all_transactions');

                //wallet
                Route::get('/wallets', 'wallets')->name('wallets');
                Route::post('wallet/update/{id}', 'walletUpdate')->name('wallet.update');

                // referral
                Route::get('my-referral', 'referral')->name('referral');
                Route::get('referral/commlog', 'referralLog')->name('referral.log');
                Route::get('referral-bonus-logs', 'referralLog')->name('referral.log');

                Route::get('attachment-download/{fil_hash}', 'attachmentDownload')->name('attachment.download');
            });

            //Profile setting
            Route::controller('ProfileController')->group(function () {
                Route::get('profile-setting', 'profile')->name('profile.setting');
                Route::post('profile-setting', 'submitProfile');
                Route::get('change-password', 'changePassword')->name('change.password');
                Route::post('change-password', 'submitPassword');
            });

            //Buy Plan
            Route::controller('OrderPlanController')->group(function () {
                Route::get('mining-plans', 'plans')->name('plans');
                Route::post('plan/order', 'orderPlan')->name('plan.order');
                Route::get('mining-tracks', 'miningTracks')->name('plans.purchased');
            });

            // Withdraw
            Route::controller('WithdrawController')->prefix('withdraw')->name('withdraw')->group(function () {
                Route::middleware('kyc')->group(function () {
                    Route::get('/', 'withdrawMoney');
                    Route::post('/', 'withdrawStore')->name('.money');
                    Route::post('/referral-commission', 'withdrawReferralCommission')->name('.referral.commission');
                    Route::get('preview/{id}', 'withdrawPreview')->name('.preview');
                });
                Route::get('my-withdrawals', 'withdrawLog')->name('.history');
            });
        });

        // Payment
        Route::middleware('registration.complete')->controller('Gateway\PaymentController')->group(function () {
            Route::any('/deposit', 'deposit')->name('index');

            // Route::get('deposit', 'index')->name('deposit.index');

            Route::any('/payment/{id}', 'payment')->name('payment');
            Route::post('deposit/insert', 'depositInsert')->name('deposit.insert');
            Route::get('deposit/confirm', 'depositConfirm')->name('deposit.confirm');
            Route::get('deposit/manual', 'manualDepositConfirm')->name('deposit.manual.confirm');
            Route::post('deposit/manual', 'manualDepositUpdate')->name('deposit.manual.update');

            Route::get('deposit/history', 'DepositHistory')->name('deposit.history');
        });
    });
});
