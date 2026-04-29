<?php
namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function dashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $stats = [
            'total_books' => Book::count(),
            'active_borrowings' => Borrowing::where('user_id', $user->id)->whereNull('return_date')->count(),
            'borrowing_history' => Borrowing::where('user_id', $user->id)->count(),
            'total_favorites' => Favorite::where('user_id', $user->id)->count(),
        ];
        $activeBorrowings = Borrowing::with('book')->where('user_id', $user->id)->whereNull('return_date')->latest()->take(5)->get();
        $recommendedBooks = Book::where('available', '>', 0)->latest()->take(6)->get();
        return view('user.dashboard', compact('stats', 'activeBorrowings', 'recommendedBooks'));
    }
    
    public function books(Request $request)
    {
        $query = Book::query();
        
        // AJAX Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('author', 'LIKE', "%{$search}%")
                  ->orWhere('isbn', 'LIKE', "%{$search}%")
                  ->orWhere('publisher', 'LIKE', "%{$search}%");
            });
        }
        
        $books = $query->latest()->paginate(24);
        
        // Return partial view for AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return view('user.books', compact('books'));
        }
        
        return view('user.books', compact('books'));
    }
    
    public function borrowings()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $borrowings = Borrowing::with('book')->where('user_id', $user->id)->whereNull('return_date')->latest()->get();
        return view('user.borrowings', compact('borrowings'));
    }
    
    public function history()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $history = Borrowing::with('book')->where('user_id', $user->id)->whereNotNull('return_date')->latest()->get();
        return view('user.history', compact('history'));
    }
    
    public function favorites()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $favorites = Favorite::with('book')->where('user_id', $user->id)->latest()->get();
        return view('user.favorites', compact('favorites'));
    }
    
    public function account()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view('user.akun', compact('user'));
    }
    
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
        ]);
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();
        return redirect()->route('user.account')->with('success', 'Akun berhasil diupdate');
    }
    
    public function destroy()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (Borrowing::where('user_id', $user->id)->whereNull('return_date')->exists()) {
            return back()->with('error', 'Tidak dapat menghapus akun. Masih ada peminjaman aktif.');
        }
        Auth::logout();
        $user->delete();
        return redirect()->route('login')->with('success', 'Akun berhasil dihapus');
    }
}