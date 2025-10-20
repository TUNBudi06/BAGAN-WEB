<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BaseRoute;
use Illuminate\Support\Facades\Route;

Route::get('/',[BaseRoute::class,'index'])->name('base');
Route::get('base/{id}',[BaseRoute::class,'showBagan'])->name('show-bagan');
Route::group(['prefix'=>'admin'], function() {
    Route::get('base/{id}/edit',[BaseRoute::class,'editBagan'])->name('edit-bagan');
    Route::post('base/{id}/update',[BaseRoute::class,'updateBagan'])->name('update-bagan');
    Route::get('base/{id}/delete',[BaseRoute::class,'deleteBagan'])->name('delete-bagan');
});

Route::prefix('account')->group(function () {
    Route::get('login', function () {
        return view('Account.Login');
    })->name('login');
    Route::post('logout',[AdminController::class,'logout'])->name('logout');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('dashboard',[AdminController::class,'index'])->name('admin');
    Route::get('BaganList',[AdminController::class,'BaganList'])->name('bagan-list');
    Route::get('BaganeEdit/{id}',[AdminController::class,'BaganeEdit'])->name('bagan-edit');
});

