<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\EmailOtpController;
use App\Http\Controllers\SmsOtpController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::view('/login', 'auth.login')->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

Route::view('/register', 'auth.register')->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/otp/phone', [SmsOtpController::class, 'create'])->name('otp.phone');
Route::post('/otp/phone', [SmsOtpController::class, 'send'])->name('otp.phone.send');
Route::get('/otp/email', [EmailOtpController::class, 'create'])->name('otp.email');
Route::post('/otp/email', [EmailOtpController::class, 'send'])->name('otp.email.send');
Route::get('/otp/email/verify', [EmailOtpController::class, 'verifyForm'])->name('otp.email.verify');
Route::post('/otp/email/verify', [EmailOtpController::class, 'verify'])->name('otp.email.verify.store');
Route::post('/otp/email/resend', [EmailOtpController::class, 'resend'])->name('otp.email.resend');
Route::get('/otp/verify', [SmsOtpController::class, 'verifyForm'])->name('otp.verify');
Route::post('/otp/verify', [SmsOtpController::class, 'verify'])->name('otp.verify.store');
Route::post('/otp/resend', [SmsOtpController::class, 'resend'])->name('otp.resend');

Route::middleware('auth')->group(function (): void {
    Route::redirect('/dashboard', '/mailbox')->name('dashboard');
    Route::view('/mailbox', 'mailbox')->name('mailbox');
    Route::view('/ai-chatbot', 'ai-chatbot')->name('ai-chatbot');
});

Route::redirect('/index.html', '/');
Route::redirect('/otp-phone.html', '/otp/phone');
Route::redirect('/otp-email.html', '/otp/email');
Route::redirect('/validate-otp.html', '/otp/verify');
Route::redirect('/mailbox.html', '/mailbox');
Route::redirect('/ai-chatbot.html', '/ai-chatbot');
