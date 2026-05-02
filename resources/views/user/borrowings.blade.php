@extends('layouts.app-navbar')
@section('title', 'Peminjaman - Digishelf')

@section('content')
    <style>
        .section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08)
        }

        .borrowing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px
        }

        .borrowing-card {
            background: var(--cream);
            border-radius: 10px;
            padding: 20px;
            border-left: 4px solid var(--wood-medium)
        }

        .borrowing-card h3 {
            color: var(--wood-dark);
            margin-bottom: 10px
        }

        .borrowing-card p {
            color: #666;
            font-size: 0.9rem;
            margin: 5px 0
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 10px
        }

        .status-badge.active {
            background: #E8F5E9;
            color: #2E7D32
        }

        .status-badge.overdue {
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
        <h2 style="font-family:'Crimson Pro',serif;font-size:1.5rem;margin-bottom:20px;color:var(--text-dark)">Buku yang
            Sedang Dipinjam</h2>
        @if ($borrowings->count() > 0)
            <div class="borrowing-grid">
                @foreach ($borrowings as $b)
                    <div class="borrowing-card">
                        <h3>{{ $b->book->title }}</h3>
                        <p><i class="fas fa-calendar"></i> Dipinjam: {{ $b->borrowed_date->format('d M Y') }}</p>
                        <p><i class="fas fa-clock"></i> Jatuh Tempo: {{ $b->due_date->format('d M Y') }}</p>
                        <span
                            class="status-badge {{ $b->status }}">{{ $b->status === 'overdue' ? 'Terlambat' : 'Aktif' }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state"><i class="fas fa-book-open"></i>
                <p>Belum ada peminjaman</p>
            </div>
        @endif
    </div>
    <form id="delete-form" action="{{ route('profile.delete') }}" method="POST" style="display:none">@csrf
        @method('DELETE')</form>
@endsection