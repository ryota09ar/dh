<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//create & edit user
Route::get("users/create", [UserController::class, "create"])->name("user.create");
Route::post("users/create/", [UserController::class, "store"])->name("user.store");
Route::get("users/create/complete", [UserController::class, "createComplete"])->name("user.createComplete");
Route::get("users/update/complete", [UserController::class, "updateComplete"])->name("user.updateComplete");
Route::get("users/edit", [UserController::class, "edit"])->name("user.edit");
Route::post("users/edit/", [UserController::class, "update"])->name("user.update");

//necessary login
Route::get("home", [UserController::class, "home"])->name("user.home");
Route::get("shifts/create", [ShiftController::class, "create"])->name("shift.create");
Route::post("shifts/create/", [ShiftController::class, "store"])->name("shift.store");

//login
Route::get("login", [LoginController::class, "show"])->name("login");
Route::post("login/", [LoginController::class, "login"])->name("login.home");
Route::get("home", [UserController::class, "home"])->name("user.home");
Route::get("logout", [LoginController::class, "logout"])->name("logout");
