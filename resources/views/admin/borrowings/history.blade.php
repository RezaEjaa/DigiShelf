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
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .section-title {
        font-size: 1.1rem;
        color: var(--text-dark);
        margin-bottom: 22px;
        font-weight: 600;
    }

    .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }

    .borrowing-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 560px;
    }

    .borrowing-table th {
        background: var(--cream);
        padding: 13px 14px;
        text-align: left;
        font-weight: 600;
        color: var(--text-dark);
        border-bottom: 2px solid #E0E0E0;
        font-size: 0.88rem;
        white-space: nowrap;
    }

    .borrowing-table td {
        padding: 13px 14px;
        border-bottom: 1px solid #F5F5F5;
        font-size: 0.88rem;
        vertical-align: middle;
    }

    .borrowing-table tr:hover { background: #FAFAFA; }

    .badge-returned {
        padding: 4px 11px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge-ontime  { background: #E8F5E9; color: #2E7D32; }
    .badge-late    { background: #FFEBEE; color: #C62828; }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 22px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .pagination-info { color: #94A3B8; font-size: 0.85rem; }
    .pagination-controls { display: flex; gap: 6px; flex-wrap: wrap; }

    .pagination-controls a,
    .pagination-controls span {
        min-width: 33px;
        height: 33px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 7px;
        text-decoration: none;
        font-size: 0.85rem;
    }
    .pagination-controls a { background: #F1F5F9; color: #64748B; }
    .pagination-controls a:hover { background: var(--wood-medium); color: white; }
    .pagination-controls .active { background: var(--wood-dark); color: white; }

    /* ===========================
       RESPONSIVE
    =========================== */
    @media (max-width: 768px) {
        .section { padding: 16px; }
        .borrowing-table th,
        .borrowing-table td { padding: 10px 10px; font-size: 0.82rem; }
    }

    @media (max-width: 480px) {
        .section { padding: 12px; }
    }
</style>

<div class="section">
    <h2 class="section-title">Riwayat Pengembalian ({{ $borrowings->total() }})</h2>

    @if($borrowings->count() > 0)
        <div class="table-responsive">
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
                                @if($borrowing->return_date > $borrowing->due_date)
                                    <span class="badge-returned badge-late">Terlambat</span>
                                @else
                                    <span class="badge-returned badge-ontime">Tepat Waktu</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

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
        <div style="text-align:center;padding:60px 20px;color:#999;">
            <i class="fas fa-history" style="font-size:56px;opacity:0.3;"></i>
            <h3 style="margin-top:16px;font-size:1rem;">Belum ada riwayat peminjaman</h3>
        </div>
    @endif
</div>
@endsection