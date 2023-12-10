<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BkashPaymentController;

use App\Http\Controllers\SslCommerzPaymentController;

use App\Http\Controllers\BkashController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['auth']], function () {

    // Payment Routes for bKash
    Route::get('/bkash/payment', [BkashPaymentController::class,'index']);
    Route::post('/bkash/get-token', [BkashPaymentController::class,'getToken'])->name('bkash-get-token');
    Route::post('/bkash/create-payment', [BkashPaymentController::class,'createPayment'])->name('bkash-create-payment');
    Route::post('/bkash/execute-payment', [BkashPaymentController::class,'executePayment'])->name('bkash-execute-payment');
    Route::get('/bkash/query-payment', [BkashPaymentController::class,'queryPayment'])->name('bkash-query-payment');
    Route::post('/bkash/success', [BkashPaymentController::class,'bkashSuccess'])->name('bkash-success');

    // Refund Routes for bKash
    Route::get('/bkash/refund', [BkashPaymentController::class,'refundPage'])->name('bkash-refund');
    Route::post('/bkash/refund', [BkashPaymentController::class,'refund'])->name('bkash-refund');

});

Route::get('nagad/pay',[App\Http\Controllers\NagadController::class,'pay'])->name('nagad.pay');
Route::get('nagad/callback', [App\Http\Controllers\NagadController::class,'callback'])->name('nagad.callback');
Route::get('nagad/refund/{paymentRefId}', [App\Http\Controllers\NagadController::class,'refund']);


// SSLCOMMERZ Start
Route::get('/example1', [SslCommerzPaymentController::class, 'exampleEasyCheckout']);
Route::get('/example2', [SslCommerzPaymentController::class, 'exampleHostedCheckout']);

Route::post('/pay', [SslCommerzPaymentController::class, 'index']);
Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'payViaAjax']);

Route::post('/success', [SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);

Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);
//SSLCOMMERZ END

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

 // Payment Routes for bKash
    Route::post('/token', [BkashController::class,'token'])->name('token');
    Route::get('/bkash/create-payment', [BkashController::class,'createPayment'])->name('bkash-create-payment');
    Route::get('/bkash/execute-payment', [BkashController::class,'executePayment'])->name('bkash-execute-payment');