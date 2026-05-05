<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

Route::view('/otp/phone', 'otp.phone')->name('otp.phone');
Route::view('/otp/email', 'otp.email')->name('otp.email');
Route::view('/otp/verify', 'otp.verify')->name('otp.verify');

Route::view('/mailbox', 'mailbox')->name('mailbox');
Route::view('/ai-chatbot', 'ai-chatbot')->name('ai-chatbot');

Route::redirect('/index.html', '/');
Route::redirect('/otp-phone.html', '/otp/phone');
Route::redirect('/otp-email.html', '/otp/email');
Route::redirect('/validate-otp.html', '/otp/verify');
Route::redirect('/mailbox.html', '/mailbox');
Route::redirect('/ai-chatbot.html', '/ai-chatbot');
