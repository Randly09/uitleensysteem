<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MateriaalController;
use App\Http\Controllers\Admin\MateriaalSetController;
use App\Http\Controllers\Admin\LogboekController;
use App\Http\Controllers\Admin\RetourController;
use App\Http\Controllers\User\LenenController as UserLenenController;

Route::redirect('/', '/admin');

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
*/

Route::get('/admin', function () {
    return view('admin.home');
})->name('admin.home');

Route::get('/admin/materialen', [MateriaalController::class, 'index'])
    ->name('admin.materialen');

Route::post('/admin/materialen', [MateriaalController::class, 'store'])
    ->name('admin.materialen.store');

Route::put('/admin/materialen/{materiaal}', [MateriaalController::class, 'update'])
    ->name('admin.materialen.update');

Route::post('/admin/sets', [MateriaalSetController::class, 'store'])
    ->name('admin.sets.store');

Route::put('/admin/sets/{set}', [MateriaalSetController::class, 'update'])
    ->name('admin.sets.update');

Route::get('/admin/logboek', [LogboekController::class, 'index'])
    ->name('admin.logboek');

Route::patch('/admin/logboek/{logboek}/terug', [LogboekController::class, 'toggleTerug'])
    ->name('admin.logboek.terug');

Route::get('/admin/retouren', [RetourController::class, 'index'])
    ->name('admin.retouren');

Route::post('/admin/retouren/verwerken', [RetourController::class, 'process'])
    ->name('admin.retouren.process');

/*
|--------------------------------------------------------------------------
| User routes
|--------------------------------------------------------------------------
*/

Route::get('/user', function () {
    return view('user.home');
})->name('user.home');

Route::get('/user/lenen', [UserLenenController::class, 'create'])
    ->name('user.lenen');

Route::post('/user/lenen', [UserLenenController::class, 'store'])
    ->name('user.lenen.store');

Route::get('/user/geleend', function () {
    return redirect()->route('user.lenen');
})->name('user.geleend');

Route::get('/user/profiel', function () {
    return view('user.profiel');
})->name('user.profiel');