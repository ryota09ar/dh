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

//login
Route::get("login", [LoginController::class, "show"])->name("login");
Route::post("login/", [LoginController::class, "login"])->name("login.home");
Route::get("logout", [LoginController::class, "logout"])->name("logout");

//necessary login
Route::group(['middleware' => ['auth:web']], function () {
    Route::get("users/home", [UserController::class, "home"])->name("user.home");
    Route::get("users/request/create", [ShiftController::class, "requestCreate"])->name("shiftRequest.create");
    Route::post("users/request/create/", [ShiftController::class, "requestStore"])->name("shiftRequest.store");
    Route::get("users/shifts/index", [ShiftController::class, "decidedIndex"])->name("decided.index");
});

//admin login
Route::get("admin/login", [LoginController::class, "adminShow"])->name("admin.login");
Route::post("admin/login/", [LoginController::class, "adminLogin"])->name("admin.login.home");
Route::get("admin/logout", [LoginController::class, "adminLogout"])->name("admin.logout");

//admin
Route::group(['middleware' => ['auth:admin']], function () {
    Route::get("admin/menu", [ShiftAdminController::class, "show"])->name("admin.menu");
    //edit place
    Route::get("admin/shifts/place", [ShiftAdminController::class, "placeIndex"])->name("shiftPlace.index");
    Route::get("admin/shifts/place/create", [ShiftAdminController::class, "placeCreate"])->name("shiftPlace.create");
    Route::post("admin/shifts/place/", [ShiftAdminController::class, "placeStore"])->name("shiftPlace.store");
    Route::get("admin/shifts/place/{id}", [ShiftAdminController::class, "placeEdit"])->name("shiftPlace.edit");
    Route::post("admin/shifts/place/{id}", [ShiftAdminController::class, "placeUpdate"])->name("shiftPlace.update");
    //look for shift
    Route::get("admin/shifts/lookFor", [ShiftAdminController::class, "lookForCreate"])->name("shiftLookFor.create");
    Route::post("admin/shifts/lookFor/", [ShiftAdminController::class, "lookForStore"])->name("shiftLookFor.store");
    Route::post("admin/shifts/lookFor/xlsxOutput", [ShiftAdminController::class, "exportLookForShiftsToExcel"])->name("shiftLookFor.excel");
    Route::post("admin/shifts/lookFor/confirmed", [ShiftAdminController::class, "lookForConfirmation"])->name("shiftLookFor.confirm");
    //edit shift
    Route::get("admin/shifts/decide/create", [ShiftAdminController::class, "decideCreate"])->name("shiftDecide.create");
    Route::post("admin/shifts/decide/create/", [ShiftAdminController::class, "decideStore"])->name("shiftDecide.store");
    Route::post("admin/shifts/decide/create/expiration", [ShiftAdminController::class, "decideExpiration"])->name("shiftDecide.expiration");
    //index shift
    Route::get("admin/shifts/index", [ShiftAdminController::class, "decidedIndex"])->name("shiftDecided.index");
    Route::post("admin/shifts/index/excelOutput", [ShiftAdminController::class, "exportDecidedShiftsToExcel"])->name("shiftDecided.excel");
});
