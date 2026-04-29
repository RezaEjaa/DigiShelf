@extends('layouts.app-navbar')

@section('title', 'Dashboard - Digishelf')

@section('navbar-menu')
    <li><a href="{{ route('user.dashboard') }}" class="active"><i class="fas fa-th-large"></i> Dashboard</a></li>
    <li><a href="{{ route('user.books') }}"><i class="fas fa-book"></i> Koleksi Buku</a></li>
    <li><a href="{{ route('user.borrowings') }}"><i class="fas fa-book-reader"></i> Peminjaman</a></li>
    <li><a href="{{ route('user.history') }}"><i class="fas fa-history"></i> Riwayat</a></li>
    <li><a href="{{ route('user.favorites') }}"><i class="fas fa-heart"></i> Favorit</a></li>
    <li><a href="{{ route('user.account') }}"><i class="fas fa-user-circle"></i> Akun</a></li>
@endsection

@section('content')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, var(--wood-medium), var(--wood-dark));
        color: white;
        border-radius: 20px;
        padding: 50px 40px;
        margin-bottom: 30px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .welcome-banner h1 {
        font-family: 'Crimson Pro', serif;
        font-size: 2.5rem;
        margin-bottom: 10px;
    }
    .welcome-banner p {
        font-size: 1.1rem;
        opacity: 0.95;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }
    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }
    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .stat-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--wood-medium), var(--wood-dark));
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .stat-icon i {
        font-size: 28px;
        color: white;
    }
    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--wood-dark);
        margin-bottom: 5px;
    }
    .stat-label {
        color: #666;
        font-size: 0.95rem;
    }
    
    .section {
        background: white;
        border-radius: 20px;
        padding: 35px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        margin-bottom: 30px;
    }
    .section-title {
        font-family: 'Crimson Pro', serif;
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .view-all {
        font-size: 0.9rem;
        color: var(--wood-medium);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s;
    }
    .view-all:hover {
        color: var(--wood-dark);
    }
    
    .borrowing-list {
        display: grid;
        gap: 15px;
    }
    .borrowing-item {
        background: var(--cream);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s;
    }
    .borrowing-item:hover {
        transform: translateX(5px);
    }
    .borrowing-info h4 {
        color: var(--text-dark);
        font-size: 1.05rem;
        margin-bottom: 5px;
        font-weight: 600;
    }
    .borrowing-info p {
        color: #666;
        font-size: 0.85rem;
        margin: 0;
    }
    .borrowing-status {
        text-align: right;
    }
    .status-badge {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 5px;
    }
    .status-badge.active {
        background: #E8F5E9;
        color: #2E7D32;
    }
    .status-badge.overdue {
        background: #FFEBEE;
        color: #C62828;
    }
    .due-date {
        color: #666;
        font-size: 0.8rem;
    }
    
    .books-showcase {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 25px;
    }
    .book-showcase-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s;
        cursor: pointer;
    }
    .book-showcase-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .book-cover-showcase {
        width: 100%;
        height: 250px;
        background: linear-gradient(135deg, #A1887F, #8D6E63);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .book-cover-showcase img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .book-cover-showcase i {
        font-size: 48px;
        color: rgba(255,255,255,0.5);
    }
    .book-info-showcase {
        padding: 15px;
    }
    .book-title-showcase {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 5px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .book-author-showcase {
        font-size: 0.85rem;
        color: #666;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }
    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
    }
    
    @media (max-width: 768px) {
        .welcome-banner {
            padding: 35px 25px;
        }
        .welcome-banner h1 {
            font-size: 2rem;
        }
        .stats-grid {
            grid-template-columns: 1fr;
        }
        .borrowing-item {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }
        .borrowing-status {
            text-align: center;
        }
        .books-showcase {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
        }
    }
</style>

<div class="welcome-banner">
    <h1>Selamat Datang, {{ Auth::user()->name }}!</h1>
    <p>Jelajahi koleksi buku digital dan kelola peminjaman Anda dengan mudah</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $stats['total_books'] }}</div>
                <div class="stat-label">Total Buku</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-book"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $stats['active_borrowings'] }}</div>
                <div class="stat-label">Sedang Dipinjam</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-book-open"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $stats['borrowing_history'] }}</div>
                <div class="stat-label">Riwayat Peminjaman</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-history"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $stats['total_favorites'] }}</div>
                <div class="stat-label">Total Favorit</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-heart"></i>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-title">
        <span>Peminjaman Aktif</span>
        <a href="{{ route('user.borrowings') }}" class="view-all">Lihat Semua →</a>
    </div>
    
    @if($activeBorrowings->count() > 0)
        <div class="borrowing-list">
            @foreach($activeBorrowings as $borrowing)
            <div class="borrowing-item">
                <div class="borrowing-info">
                    <h4>{{ $borrowing->book->title }}</h4>
                    <p><i class="fas fa-calendar"></i> Dipinjam: {{ $borrowing->borrowed_date->format('d M Y') }}</p>
                </div>
                <div class="borrowing-status">
                    @if($borrowing->status === 'overdue')
                        <span class="status-badge overdue">Terlambat</span>
                    @else
                        <span class="status-badge active">Aktif</span>
                    @endif
                    <div class="due-date">Jatuh tempo: {{ $borrowing->due_date->format('d M Y') }}</div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-book-open"></i>
            <p>Anda belum memiliki peminjaman aktif</p>
        </div>
    @endif
</div>

<div class="section">
    <div class="section-title">
        <span>Rekomendasi Untuk Anda</span>
        <a href="{{ route('user.books') }}" class="view-all">Lihat Semua →</a>
    </div>
    
    @if($recommendedBooks->count() > 0)
        <div class="books-showcase">
            @php
                $colors = [
                    'linear-gradient(135deg, #A1887F, #8D6E63)',
                    'linear-gradient(135deg, #7986CB, #5C6BC0)',
                    'linear-gradient(135deg, #81C784, #66BB6A)',
                    'linear-gradient(135deg, #FFB74D, #FFA726)',
                    'linear-gradient(135deg, #E57373, #EF5350)',
                    'linear-gradient(135deg, #9575CD, #7E57C2)',
                ];
            @endphp
            @foreach($recommendedBooks as $index => $book)
            <div class="book-showcase-card" onclick="window.location.href='{{ route('user.books') }}'">
                <div class="book-cover-showcase" style="background: {{ $colors[$index % 6] }};">
                    @if($book->cover_image)
                        <img src="{{ asset('img/covers/' . $book->cover_image) }}" alt="{{ $book->title }}">
                    @else
                        <i class="fas fa-book"></i>
                    @endif
                </div>
                <div class="book-info-showcase">
                    <div class="book-title-showcase">{{ $book->title }}</div>
                    <div class="book-author-showcase">{{ $book->author }}</div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-book"></i>
            <p>Belum ada buku tersedia</p>
        </div>
    @endif
</div>
@endsection