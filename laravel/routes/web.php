<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ShiftAdminController;
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

//admin
Route::get("admin/menu", [ShiftAdminController::class, "show"])->name("admin.menu");
Route::get("admin/shifts/place", [ShiftAdminController::class, "placeIndex"])->name("shiftPlace.index");
Route::get("admin/shifts/place/create", [ShiftAdminController::class, "placeCreate"])->name("shiftPlace.create");
Route::post("admin/shifts/place/", [ShiftAdminController::class, "placeStore"])->name("shiftPlace.store");
Route::get("admin/shifts/place/{id}", [ShiftAdminController::class, "placeEdit"])->name("shiftPlace.edit");
Route::post("admin/shifts/place/{id}", [ShiftAdminController::class, "placeUpdate"])->name("shiftPlace.update");
Route::get("admin/lookForYearMonth", [ShiftAdminController::class, "lookForYearMonth"])->name("shiftLookFor.yearMonth");
Route::get("admin/shifts/lookFor", [ShiftAdminController::class, "lookForCreate"])->name("shiftLookFor.create");
Route::post("admin/shifts/lookFor/", [ShiftAdminController::class, "lookForStore"])->name("shiftLookFor.store");
Route::post("admin/shifts/lookFor/xlsxOutput", [ShiftAdminController::class, "exportLookForShiftsToExcel"])->name("shiftLookFor.excel");

//login
Route::get("login", [LoginController::class, "show"])->name("login");
Route::post("login/", [LoginController::class, "login"])->name("login.home");
Route::get("home", [UserController::class, "home"])->name("user.home");
Route::get("logout", [LoginController::class, "logout"])->name("logout");
