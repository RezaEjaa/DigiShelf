<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookApiController;

/*
|--------------------------------------------------------------------------
| API Routes for Books
|--------------------------------------------------------------------------
*/

Route::get('/books/{id}', [BookApiController::class, 'show'])->name('api.books.show');