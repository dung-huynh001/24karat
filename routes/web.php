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
Route::get('/manager/register', [ManagerController::class, 'register'])->name('manager.register');
Route::get('/manager/edit/{id}', [ManagerController::class, 'edit'])->name('manager.edit');
Route::get('/manager/get-managers', [ManagerController::class, 'getManagers'])->name('manager.get-managers');
