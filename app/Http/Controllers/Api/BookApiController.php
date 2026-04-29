<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\JsonResponse;

class BookApiController extends Controller
{
    /**
     * Display the specified book.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $book = Book::findOrFail($id);
            
            return response()->json([
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'isbn' => $book->isbn,
                'publisher' => $book->publisher,
                'publication_year' => $book->publication_year,
                'description' => $book->description,
                'stock' => $book->stock,
                'available' => $book->available,
                'cover_image' => $book->cover_image,
            ], 200);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Book not found',
                'message' => 'The requested book does not exist.'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => 'An error occurred while fetching the book details.'
            ], 500);
        }
    }
}