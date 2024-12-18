<?php

use App\Http\Controllers\DynamicFieldController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SubscriptionUserController;
use Illuminate\Support\Facades\Auth;



Auth::routes();

Route::get("/", function () {
    return redirect()->route("home");
});
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/manager/list', [ManagerController::class, 'index'])->name('manager.list');
Route::get('/manager/register', [ManagerController::class, 'register'])->name('manager.register');
Route::post('/manager/registerAPI', [ManagerController::class, 'registerAPI'])->name('manager.registerAPI');
Route::post('/manager/store', [ManagerController::class, 'store'])->name('manager.store');
Route::get('/manager/edit/{id}', [ManagerController::class, 'edit'])->name('manager.edit');
Route::patch('/manager/update/{id}', [ManagerController::class, 'update'])->name('manager.update');
Route::delete('/manager/delete/{id}', [ManagerController::class, 'delete'])->name('manager.delete');
Route::get('/manager/get-managers', [ManagerController::class, 'getManagers'])->name('manager.get-managers');

Route::get('/subscription_user/list', [SubscriptionUserController::class, 'index'])->name('subscription_user.list');
Route::get('/subscription_user/add', [SubscriptionUserController::class, 'add'])->name('subscription_user.add');
Route::post('/subscription_user/create', [SubscriptionUserController::class, 'create'])->name('subscription_user.create');
Route::get('/subscription_user/edit/{id}', [SubscriptionUserController::class, 'edit'])->name('subscription_user.edit');
Route::patch('/subscription_user/update/{id}', [SubscriptionUserController::class, 'update'])->name('subscription_user.update');
Route::delete('/subscription_user/delete/{id}', [SubscriptionUserController::class, 'delete'])->name('subscription_user.delete');
Route::get('/subscription_user/get-subscription_users', [SubscriptionUserController::class, 'getSubscriptionUsers'])->name('subscription_user.get-subscription_users');
Route::get('/subscription_user/autofill_address1/{code}', [SubscriptionUserController::class, 'autoFillAddress1'])->name('subscription_user.autofill_address1');


Route::get('/dynamic_field/list', [DynamicFieldController::class, 'index'])->name('dynamic_field.list');
Route::get('/dynamic_field/add', [DynamicFieldController::class, 'add'])->name('dynamic_field.add');
Route::post('/dynamic_field/create', [DynamicFieldController::class, 'create'])->name('dynamic_field.create');
Route::get('/dynamic_field/edit/{id}', [DynamicFieldController::class, 'edit'])->name('dynamic_field.edit');
Route::patch('/dynamic_field/update/{id}', [DynamicFieldController::class, 'update'])->name('dynamic_field.update');
Route::delete('/dynamic_field/delete/{id}', [DynamicFieldController::class, 'delete'])->name('dynamic_field.delete');
Route::get('/dynamic_field/get-dynamic_fields', [DynamicFieldController::class, 'getFields'])->name('dynamic_field.get-dynamic_fields');
