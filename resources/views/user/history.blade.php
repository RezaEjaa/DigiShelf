@extends('layouts.app')
@section('title', 'Riwayat - Digishelf')
@section('page-title', 'Riwayat Peminjaman')
@section('page-subtitle', 'Buku yang pernah dipinjam')
@section('sidebar-menu')
    <li>
        <a href="{{ route('user.dashboard') }}">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('user.books') }}">
            <i class="fas fa-book"></i>
            <span>Koleksi Buku</span>
        </a>
    </li>
    <li>
        <a href="{{ route('user.borrowings') }}">
            <i class="fas fa-book-reader"></i>
            <span>Peminjaman</span>
        </a>
    </li>
    <li>
        <a href="{{ route('user.history') }}" class="active">
            <i class="fas fa-history"></i>
            <span>Riwayat Peminjaman</span>
        </a>
    </li>
    <li>
        <a href="{{ route('user.favorites') }}">
            <i class="fas fa-heart"></i>
            <span>Favorit Saya</span>
        </a>
    </li>
    <li class="logout-section">
        <a href="{{ route('user.account') }}">
            <i class="fas fa-user-circle"></i>
            <span>Akun</span>
        </a>
    </li>
    <li>
        <form action="{{ route('logout') }}" method="POST" class="logout-form">
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
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08)
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px
        }

        .history-table thead {
            background: var(--cream)
        }

        .history-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: var(--text-dark)
        }

        .history-table td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600
        }

        .status-badge.returned {
            background: #E8F5E9;
            color: #2E7D32
        }

        .status-badge.late {
            background: #FFEBEE;
            color: #C62828
        }

        .empty-state {
            text-align: center;
            padding: 60px;
            color: #999
        }

        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.5
        }
    </style>
    <div class="section">
        <h2 style="font-family:'Crimson Pro',serif;font-size:1.5rem;margin-bottom:20px;color:var(--text-dark)">Riwayat
            Peminjaman</h2>
        @if ($history->count() > 0)
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Buku</th>
                        <th>Dipinjam</th>
                        <th>Dikembalikan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($history as $h)
                        <tr>
                            <td><strong>{{ $h->book->title }}</strong></td>
                            <td>{{ $h->borrowed_date->format('d M Y') }}</td>
                            <td>{{ $h->return_date ? $h->return_date->format('d M Y') : '-' }}</td>
                            <td><span
                                    class="status-badge {{ $h->return_date && $h->return_date > $h->due_date ? 'late' : 'returned' }}">
                                    {{ $h->return_date && $h->return_date > $h->due_date ? 'Terlambat' : 'Tepat Waktu' }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state"><i class="fas fa-history"></i>
                <p>Belum ada riwayat</p>
            </div>
        @endif
    </div>
    <form id="delete-form" action="{{ route('profile.delete') }}" method="POST" style="display:none">@csrf
        @method('DELETE')</form>
@endsection
