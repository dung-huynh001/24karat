<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\HomeController;

Auth::routes();

Route::get("/", function () {
    return redirect()->route("home");
});
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/manager/list', [ManagerController::class, 'index'])->name('manager.list');
Route::get('/manager/get-managers', [ManagerController::class, 'getManagers'])->name('manager.get-managers');
