<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', [HomeController::class, 'index']);
Route::get('/about', function () {
    return view('about', ['title' => 'About']);
});

Route::get('/coba', function () {
    return view('coba');
});



Route::get('/hall', [HallController::class, 'index']);
Route::get('/hall/{book:slug}', action: [HallController::class, 'detailBook']);
Route::get('/hall/author/{author:slug}', [HallController::class, 'bookByAuthor']);
Route::get('/hall/category/{category:slug}', [HallController::class, 'bookByCategory']);

Route::get('/login', [LoginController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate'])->middleware('guest');
Route::get('/registration', [LoginController::class, 'registration'])->middleware('guest');
Route::post('/registration', [LoginController::class, 'store'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth');

Route::get('/borrow/{user:slug}', [BorrowController::class, 'userIndex'])->middleware('auth');
Route::post('/borrow', [BorrowController::class, 'store'])->middleware('auth');

Route::prefix('dashboard')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/', function () {
        return view('dashboard.dashboard', ['title' => 'Dashboard']);
    })->middleware(['auth', 'isAdmin']);

    Route::get('/category', [CategoryController::class, 'index']);
    Route::get('/category/create', [CategoryController::class, 'create']);
    Route::post('/category', [CategoryController::class, 'store']);
    Route::get('/category/{category:slug}/edit', [CategoryController::class, 'edit']);
    Route::put('/category/{category:slug}', [CategoryController::class, 'update']);
    Route::delete('/category/{category:slug}', [CategoryController::class, 'destroy']);

    Route::resource('author', AuthorController::class);
    Route::resource('user', UserController::class);
    Route::resource('book', BookController::class);

    Route::get('/borrow', [BorrowController::class, 'index']);
    Route::get('/borrow/{borrow}/edit', [BorrowController::class, 'edit']);
    Route::put('/borrow/{borrow}', [BorrowController::class, 'update']);
    Route::delete('/borrow/{borrow}', [BorrowController::class, 'destroy']);
});
