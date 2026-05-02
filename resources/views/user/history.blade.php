@extends('layouts.app-navbar')

@section('title', 'Riwayat Peminjaman - Digishelf')



@section('content')
<style>
    .section {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .history-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 540px;
    }

    .history-table th {
        background: var(--cream);
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: var(--text-dark);
        border-bottom: 2px solid #E0E0E0;
        white-space: nowrap;
    }

    .history-table td {
        padding: 15px;
        border-bottom: 1px solid #F5F5F5;
        font-size: 0.92rem;
    }

    .history-table tr:hover td { background: #FAFAFA; }

    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-badge.returned {
        background: #E8F5E9;
        color: #2E7D32;
    }

    .status-badge.late {
        background: #FFEBEE;
        color: #C62828;
    }

    .empty-state {
        text-align: center;
        padding: 60px;
        color: #999;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
        display: block;
    }

    @media (max-width: 768px) {
        .section { padding: 20px 16px; }
    }
</style>

<div class="section">
    <h2 style="margin-bottom: 25px;">Riwayat Peminjaman ({{ $history->count() }})</h2>

    @if($history->count() > 0)
        <div style="overflow-x: auto;">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Buku</th>
                        <th>Dipinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Dikembalikan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $h)
                        <tr>
                            <td><strong>{{ $h->book->title }}</strong></td>
                            <td>{{ $h->borrowed_date->format('d M Y') }}</td>
                            <td>{{ $h->due_date->format('d M Y') }}</td>
                            <td>{{ $h->return_date ? $h->return_date->format('d M Y') : '-' }}</td>
                            <td>
                                <span class="status-badge {{ $h->return_date && $h->return_date > $h->due_date ? 'late' : 'returned' }}">
                                    {{ $h->return_date && $h->return_date > $h->due_date ? 'Terlambat' : 'Tepat Waktu' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-history"></i>
            <p>Belum ada riwayat peminjaman</p>
        </div>
    @endif
</div>
@endsection