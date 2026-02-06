<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CopyController;
use App\Http\Controllers\LendingController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

//bárki hozzáférhet

Route::get('/special-authors/{spec}', [BookController::class, "specialAuthors"]);
Route::post('/register',[RegisteredUserController::class, 'store']);
Route::post('/login',[AuthenticatedSessionController::class, 'store']);

//autentikált felh-ó
Route::middleware(['auth:sanctum'])
->group(function () {
    
    Route::get("/my-lendings-with-copies", [LendingController::class, "myLendingsWithCopies"]);
    Route::patch("/update-password", [UserController::class, "updatePassword"]);
    Route::get("/my-lendings-at-me", [LendingController::class, "myLendingsAtMe"]);
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
    Route::post('/rave/{copy_id}', [UserController::class, 'rawExample']);
});

//admin
Route::middleware(['auth:sanctum', Admin::class])
->group(function () {
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::get("/book-reserved-count/{id}", [BookController::class, "bookReservedCount"]);
    Route::get("/book-with-reservations", [BookController::class, "booksWithReservations"]);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/books-with-copies', [BookController::class, "booksWithCopies"]);
    Route::get('/year-author-count/{year}/{author}', [CopyController::class, "yearAuthorCount"]);
});




