<?php

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
});

