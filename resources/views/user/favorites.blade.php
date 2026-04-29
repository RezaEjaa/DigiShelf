@extends('layouts.app')
@section('title', 'Favorit - Digishelf')
@section('page-title', 'Favorit Saya')
@section('page-subtitle', 'Buku favorit Anda')
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
        <a href="{{ route('user.history') }}">
            <i class="fas fa-history"></i>
            <span>Riwayat Peminjaman</span>
        </a>
    </li>
    <li>
        <a href="{{ route('user.favorites') }}" class="active">
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

        .fav-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px
        }

        .fav-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s
        }

        .fav-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12)
        }

        .fav-cover {
            width: 100%;
            height: 250px;
            background: linear-gradient(135deg, #A1887F, #8D6E63);
            display: flex;
            align-items: center;
            justify-content: center
        }

        .fav-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .fav-cover i {
            font-size: 48px;
            color: rgba(255, 255, 255, 0.5)
        }

        .fav-info {
            padding: 15px
        }

        .fav-info h3 {
            font-size: 1rem;
            color: var(--wood-dark);
            margin-bottom: 5px
        }

        .fav-info p {
            font-size: 0.85rem;
            color: #666
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
        <h2 style="font-family:'Crimson Pro',serif;font-size:1.5rem;margin-bottom:20px;color:var(--text-dark)">Buku Favorit
        </h2>
        @if ($favorites->count() > 0)
            <div class="fav-grid">
                @foreach ($favorites as $f)
                    <div class="fav-card">
                        <div class="fav-cover">
                            @if ($f->book->cover_image)
                                <img src="{{ asset('img/covers/' . $f->book->cover_image) }}" alt="{{ $f->book->title }}">
                            @else
                                <i class="fas fa-book"></i>
                            @endif
                        </div>
                        <div class="fav-info">
                            <h3>{{ $f->book->title }}</h3>
                            <p>{{ $f->book->author }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state"><i class="fas fa-heart"></i>
                <p>Belum ada favorit</p>
            </div>
        @endif
    </div>
    <form id="delete-form" action="{{ route('profile.delete') }}" method="POST" style="display:none">@csrf
        @method('DELETE')</form>
@endsection
