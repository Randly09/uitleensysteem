<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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
