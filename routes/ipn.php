<?php

use Illuminate\Support\Facades\Route;

Route::post('authorize', 'Authorize\ProcessController@ipn')->name('Authorize');
Route::any('btc-pay', 'BTCPay\ProcessController@ipn')->name('BTCPay');
Route::any('checkout', 'Checkout\ProcessController@ipn')->name('Checkout');
Route::post('coinbase-commerce', 'CoinbaseCommerce\ProcessController@ipn')->name('CoinbaseCommerce');
Route::post('coinpayments', 'Coinpayments\ProcessController@ipn')->name('Coinpayments');
Route::get('flutterwave/{trx}/{type}', 'Flutterwave\ProcessController@ipn')->name('Flutterwave');
Route::post('mercado-pago', 'MercadoPago\ProcessController@ipn')->name('MercadoPago');
Route::post('now-payments-checkout', 'NowPaymentsCheckout\ProcessController@ipn')->name('NowPaymentsCheckout');
Route::post('payeer', 'Payeer\ProcessController@ipn')->name('Payeer');
Route::get('paypal-sdk', 'PaypalSdk\ProcessController@ipn')->name('PaypalSdk');
Route::post('paystack', 'Paystack\ProcessController@ipn')->name('Paystack');
Route::post('perfect-money', 'PerfectMoney\ProcessController@ipn')->name('PerfectMoney');
Route::post('razorpay', 'Razorpay\ProcessController@ipn')->name('Razorpay');
Route::post('stripe-v3', 'StripeV3\ProcessController@ipn')->name('StripeV3');
Route::post('2checkout', 'TwoCheckout\ProcessController@ipn')->name('TwoCheckout');
Route::post('stripe-js', 'StripeJs\ProcessController@ipn')->name('StripeJs');
