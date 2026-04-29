@extends('layouts.app')

@section('title', 'Dashboard Admin - Digishelf')

@section('page-title', 'Dashboard Admin')
@section('page-subtitle', 'Kelola perpustakaan digital Anda')

@section('sidebar-menu')
    <li>
        <a href="{{ route('admin.dashboard') }}" class="active">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.books.index') }}">
            <i class="fas fa-book"></i>
            <span>Kelola Buku</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.books.create') }}">
            <i class="fas fa-plus-circle"></i>
            <span>Tambah Buku</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.borrowings.index') }}">
            <i class="fas fa-exchange-alt"></i>
            <span>Kelola Peminjaman</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.borrowings.history') }}">
            <i class="fas fa-history"></i>
            <span>Riwayat Peminjaman</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.users.index') }}">
            <i class="fas fa-users"></i>
            <span>Kelola Pengguna</span>
        </a>
    </li>
    <li class="logout-section">
        <form action="{{ url('/logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </li>
@endsection

@section('content')
<style>
    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s;
        border-left: 4px solid var(--wood-medium);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--wood-medium), var(--wood-dark));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }

    .stat-icon i {
        font-size: 28px;
        color: white;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--wood-dark);
        margin-bottom: 5px;
    }

    .stat-label {
        color: #666;
        font-size: 0.9rem;
    }

    /* Section */
    .section {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        margin-bottom: 30px;
    }

    .section-title {
        font-family: 'Crimson Pro', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--cream);
    }

    /* Borrowing Items */
    .borrowing-list {
        display: grid;
        gap: 15px;
    }

    .borrowing-item {
        background: var(--cream);
        border-radius: 10px;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s;
    }

    .borrowing-item:hover {
        transform: translateX(5px);
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }

    .borrowing-info h4 {
        color: var(--text-dark);
        font-size: 1rem;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .borrowing-info p {
        color: #666;
        font-size: 0.85rem;
        margin: 0;
    }

    .borrower-name {
        color: var(--wood-medium);
        font-weight: 500;
    }

    .borrowing-status {
        text-align: right;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .status-badge.active {
        background: #E8F5E9;
        color: #2E7D32;
    }

    .status-badge.due-soon {
        background: #FFF3E0;
        color: #E65100;
    }

    .status-badge.overdue {
        background: #FFEBEE;
        color: #C62828;
    }

    .due-date {
        color: #666;
        font-size: 0.8rem;
    }

    /* Bookshelf */
    .bookshelf-container {
        background: linear-gradient(to bottom, #8B6F47 0%, #7A5F3D 50%, #6F5539 100%);
        border-radius: 15px;
        padding: 40px 20px;
        box-shadow: inset 0 2px 10px rgba(0,0,0,0.2);
        position: relative;
    }

    .bookshelf-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: repeating-linear-gradient(
            90deg,
            transparent,
            transparent 2px,
            rgba(0,0,0,0.05) 2px,
            rgba(0,0,0,0.05) 4px
        );
        pointer-events: none;
        border-radius: 15px;
    }

    .bookshelf {
        position: relative;
        margin-bottom: 50px;
    }

    .books-row {
        display: flex;
        gap: 15px;
        padding: 20px 10px 30px;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }

    /* Book Card - COVER ONLY */
    .book-card {
        width: 160px;
        height: 240px;
        background: white;
        border-radius: 8px;
        box-shadow: 3px 3px 10px rgba(0,0,0,0.3);
        position: relative;
        cursor: pointer;
        transition: all 0.3s;
        overflow: hidden;
    }

    .book-card:hover {
        transform: translateY(-10px) rotate(2deg);
        box-shadow: 5px 10px 20px rgba(0,0,0,0.4);
    }

    .book-cover {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #A1887F, #8D6E63);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .book-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .book-cover i {
        font-size: 50px;
        color: rgba(255,255,255,0.5);
    }

    /* Add Book Card */
    .add-book-card {
        width: 160px;
        height: 240px;
        background: transparent;
        border: 3px dashed rgba(255,255,255,0.5);
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
    }

    .add-book-card:hover {
        border-color: rgba(255,255,255,0.8);
        background: rgba(255,255,255,0.1);
        transform: translateY(-5px);
    }

    .add-book-card i {
        font-size: 50px;
        color: rgba(255,255,255,0.6);
        margin-bottom: 10px;
    }

    .add-book-card span {
        color: rgba(255,255,255,0.8);
        font-size: 0.9rem;
        font-weight: 500;
    }

    .shelf-line {
        height: 12px;
        background: linear-gradient(to bottom, #6F5539 0%, #5C4A31 50%, #6F5539 100%);
        border-radius: 3px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        position: relative;
    }

    .shelf-line::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        right: 0;
        height: 6px;
        background: rgba(0,0,0,0.2);
        filter: blur(4px);
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #999;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    @media (max-width: 768px) {
        .books-row {
            justify-content: center;
        }

        .borrowing-item {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }

        .borrowing-status {
            text-align: center;
        }
    }
</style>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-book"></i>
        </div>
        <div class="stat-value">{{ $stats['total_books'] }}</div>
        <div class="stat-label">Total Buku</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-book-open"></i>
        </div>
        <div class="stat-value">{{ $stats['active_borrowings'] }}</div>
        <div class="stat-label">Sedang Dipinjam</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-value">{{ $stats['total_users'] }}</div>
        <div class="stat-label">Total Pengguna</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-value">{{ $stats['activity_rate'] }}%</div>
        <div class="stat-label">Tingkat Aktivitas</div>
    </div>
</div>

<!-- Active Borrowings -->
<div class="section">
    <h2 class="section-title">Sedang Dipinjam</h2>
    
    @if($activeBorrowings->count() > 0)
        <div class="borrowing-list">
            @foreach($activeBorrowings as $borrowing)
                <div class="borrowing-item">
                    <div class="borrowing-info">
                        <h4>{{ $borrowing->book->title }}</h4>
                        <p>Peminjam: <span class="borrower-name">{{ $borrowing->user->name }}</span></p>
                        <p>Dipinjam: {{ $borrowing->borrowed_date->format('d M Y') }}</p>
                    </div>
                    <div class="borrowing-status">
                        @if($borrowing->status === 'overdue')
                            <span class="status-badge overdue">Terlambat</span>
                        @elseif($borrowing->isDueSoon())
                            <span class="status-badge due-soon">Segera Jatuh Tempo</span>
                        @else
                            <span class="status-badge active">Aktif</span>
                        @endif
                        <p class="due-date">Jatuh tempo: {{ $borrowing->due_date->format('d M Y') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-book-open"></i>
            <p>Tidak ada peminjaman aktif saat ini</p>
        </div>
    @endif
</div>

<!-- Recent Books - 5 BUKU + 1 TOMBOL TAMBAH -->
<div class="section">
    <h2 class="section-title">Koleksi Buku Terbaru</h2>
    
    <div class="bookshelf-container">
        <div class="bookshelf">
            <div class="books-row">
                @php
                    $colors = [
                        'linear-gradient(135deg, #A1887F, #8D6E63)',
                        'linear-gradient(135deg, #7986CB, #5C6BC0)',
                        'linear-gradient(135deg, #81C784, #66BB6A)',
                        'linear-gradient(135deg, #FFB74D, #FFA726)',
                        'linear-gradient(135deg, #E57373, #EF5350)',
                    ];
                @endphp
                
                @if($latestBooks->count() > 0)
                    @foreach($latestBooks as $index => $book)
                        <div class="book-card">
                            <div class="book-cover" style="background: {{ $colors[$index % 5] }};">
                                @if($book->cover_image)
                                    <img src="{{ asset('img/covers/' . $book->cover_image) }}" alt="{{ $book->title }}">
                                @else
                                    <i class="fas fa-book"></i>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif

                <a href="{{ route('admin.books.create') }}" class="add-book-card">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Buku</span>
                </a>
            </div>
            <div class="shelf-line"></div>
        </div>
    </div>
</div>
@endsection