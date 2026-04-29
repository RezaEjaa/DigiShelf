@extends('layouts.app')

@section('title', 'Riwayat Peminjaman - Digishelf')

@section('page-title', 'Riwayat Peminjaman')
@section('page-subtitle', 'Histori peminjaman yang telah dikembalikan')

@section('sidebar-menu')
    <li>
        <a href="{{ route('admin.dashboard') }}">
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
        <a href="{{ route('admin.borrowings.history') }}" class="active">
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
    .section {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .borrowing-table {
        width: 100%;
        border-collapse: collapse;
    }

    .borrowing-table th {
        background: var(--cream);
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: var(--text-dark);
        border-bottom: 2px solid #E0E0E0;
    }

    .borrowing-table td {
        padding: 15px;
        border-bottom: 1px solid #F5F5F5;
    }

    .borrowing-table tr:hover {
        background: #FAFAFA;
    }

    .badge-returned {
        background: #E8F5E9;
        color: #2E7D32;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .pagination-wrapper {
        margin-top: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .pagination-info {
        color: #94A3B8;
        font-size: 0.9rem;
    }

    .pagination-controls {
        display: flex;
        gap: 8px;
    }

    .pagination-controls a,
    .pagination-controls span {
        min-width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .pagination-controls a {
        background: #F1F5F9;
        color: #64748B;
    }

    .pagination-controls a:hover {
        background: var(--wood-medium);
        color: white;
    }

    .pagination-controls .active {
        background: var(--wood-dark);
        color: white;
    }
</style>

<div class="section">
    <h2 style="margin-bottom: 25px;">Riwayat Pengembalian ({{ $borrowings->total() }})</h2>

    @if($borrowings->count() > 0)
        <table class="borrowing-table">
            <thead>
                <tr>
                    <th>Peminjam</th>
                    <th>Buku</th>
                    <th>Dipinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Dikembalikan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($borrowings as $borrowing)
                    <tr>
                        <td>{{ $borrowing->user->name }}</td>
                        <td><strong>{{ $borrowing->book->title }}</strong></td>
                        <td>{{ $borrowing->borrowed_date->format('d M Y') }}</td>
                        <td>{{ $borrowing->due_date->format('d M Y') }}</td>
                        <td>{{ $borrowing->return_date->format('d M Y') }}</td>
                        <td>
                            <span class="badge-returned">
                                @if($borrowing->return_date > $borrowing->due_date)
                                    Terlambat
                                @else
                                    Tepat Waktu
                                @endif
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($borrowings->hasPages())
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    Page {{ $borrowings->currentPage() }} of {{ $borrowings->lastPage() }}
                </div>
                <div class="pagination-controls">
                    @if($borrowings->onFirstPage())
                        <span>&lt;</span>
                    @else
                        <a href="{{ $borrowings->previousPageUrl() }}">&lt;</a>
                    @endif

                    @foreach(range(1, $borrowings->lastPage()) as $page)
                        @if($page == $borrowings->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $borrowings->url($page) }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($borrowings->hasMorePages())
                        <a href="{{ $borrowings->nextPageUrl() }}">&gt;</a>
                    @else
                        <span>&gt;</span>
                    @endif
                </div>
            </div>
        @endif
    @else
        <div style="text-align: center; padding: 60px; color: #999;">
            <i class="fas fa-history" style="font-size: 64px; opacity: 0.3;"></i>
            <h3 style="margin-top: 20px;">Belum ada riwayat peminjaman</h3>
        </div>
    @endif
</div>
@endsection