<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MateriaalController;

Route::redirect('/', '/admin');

Route::get('/admin', function () {
    return view('admin.home');
})->name('admin.home');

Route::get('/admin/materialen', function () {
    return view('admin.materialen');
})->name('admin.materialen');

Route::get('/admin/logboek', function () {
    return view('admin.logboek');
})->name('admin.logboek');

Route::get('/admin/retouren', function () {
    return view('admin.retouren');
})->name('admin.retouren');

Route::get('/user', function () {
    return view('user.home');
})->name('user.home');

Route::get('/user/lenen', function () {
    return view('user.lenen');
})->name('user.lenen');


Route::get('/user/profiel', function () {
    return view('user.profiel');
})->name('user.profiel');

Route::get('/admin/materialen', [MateriaalController::class, 'index'])
    ->name('admin.materialen');

Route::post('/admin/materialen', [MateriaalController::class, 'store'])
    ->name('admin.materialen.store');

    Route::put('/admin/materialen/{materiaal}', [MateriaalController::class, 'update'])
    ->name('admin.materialen.update');