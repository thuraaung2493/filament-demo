<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

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

Route::get('/', fn () => view('welcome'));

Route::redirect('/login', '/admin/login', 301)->name('login');

// Fix [login] route not defined
Route::get('/login', fn () => redirect()->route('filament.admin.auth.login'))->name('login');
