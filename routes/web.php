<?php

use App\Http\Controllers\BaseRoute;
use Illuminate\Support\Facades\Route;

Route::get('/',[BaseRoute::class,'index'])->name('base');
Route::get('base/{id}',[BaseRoute::class,'showBagan'])->name('show-bagan');
