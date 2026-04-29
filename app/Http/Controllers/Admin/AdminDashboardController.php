<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $stats = [
            'total_books' => Book::count(),
            'active_borrowings' => Borrowing::where('status', 'active')->count(),
            'total_users' => User::where('role', 'user')->count(),
            'activity_rate' => $this->calculateActivityRate(),
        ];
        
        // Get active borrowings
        Borrowing::where('status', 'active')
            ->get()
            ->each(function ($borrowing) {
                if ($borrowing->isOverdue()) {
                    $borrowing->update(['status' => 'overdue']);
                }
            });

        $activeBorrowings = Borrowing::with(['book', 'user'])
            ->where('status', 'active')
            ->orderBy('due_date', 'asc')
            ->limit(4)
            ->get();

                // Get latest books (5 books)
                $latestBooks = Book::orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();

                return view('admin.dashboard', compact('stats', 'activeBorrowings', 'latestBooks'));
            }
    
    private function calculateActivityRate()
    {
        $totalUsers = User::where('role', 'user')->count();
        if ($totalUsers === 0) return 0;
        
        $activeUsers = Borrowing::where('created_at', '>=', Carbon::now()->subMonth())
            ->distinct('user_id')
            ->count();
        
        return round(($activeUsers / $totalUsers) * 100);
    }
}