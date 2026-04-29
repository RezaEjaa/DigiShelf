<?php
namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BorrowController extends Controller
{
    public function store($bookId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $book = Book::findOrFail($bookId);
        
        if ($book->available <= 0) {
            return response()->json(['success' => false, 'message' => 'Maaf, stok buku habis'], 400);
        }
        
        $existing = Borrowing::where('user_id', $user->id)
                            ->where('book_id', $book->id)
                            ->whereNull('return_date')
                            ->first();
        
        if ($existing) {
            return response()->json(['success' => false, 'message' => 'Anda sudah meminjam buku ini'], 400);
        }
        
        Borrowing::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_date' => Carbon::now(),
            'due_date' => Carbon::now()->addDays(14),
            'status' => 'active',
        ]);
        
        $book->decrement('available');
        
        $dueDate = Carbon::now()->addDays(14)->format('d M Y');
        return response()->json([
            'success' => true, 
            'message' => "Buku berhasil dipinjam! Harap kembalikan sebelum {$dueDate}"
        ]);
    }
}