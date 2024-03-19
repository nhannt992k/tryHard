<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
//     return $request->user();
// });

Route::post("/register", [UserController::class, "store"]);
Route::get("/user/{user}", [UserController::class, "show"]);
Route::post("/update/{user}", [UserController::class, "update"]);
Route::delete("/user/{user}", [UserController::class, "destroy"]);

Route::get("/books", [BookController::class, "index"]);
Route::get("/book/{book}", [BookController::class, "show"]);

Route::get("/carts/{user}", [CartController::class, "index"]);
Route::post("/cart", [CartController::class, "store"]);
Route::delete("/cart/{cart}", [CartController::class, "destroy"]);

Route::get("/categories", [CategoryController::class,"index"]);
Route::get("/category/{category}", [CategoryController::class,"show"]);

Route::post("/address", [AddressController::class, "store"]);
Route::post("/invoice", [InvoiceController::class, "store"]);
