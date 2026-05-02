@extends('layouts.app-navbar')
@section('title', 'Favorit - Digishelf')

@section('content')
<style>
    .section-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
    .section-head h2 {
        font-family: 'Crimson Pro', serif;
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-dark);
    }

    /* Bookshelf sama seperti books-grid */
    .bookshelf-wrapper {
        background: linear-gradient(180deg, #B8956A 0%, #9A7B5A 100%);
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        position: relative;
    }
    .bookshelf-wrapper::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: repeating-linear-gradient(90deg,transparent,transparent 2px,rgba(0,0,0,0.03) 2px,rgba(0,0,0,0.03) 4px);
        border-radius: 20px;
        pointer-events: none;
    }

    .shelf-row { position: relative; margin-bottom: 50px; }
    .shelf-row:last-child { margin-bottom: 0; }
    .shelf-board {
        position: absolute;
        bottom: -25px; left: -20px; right: -20px;
        height: 15px;
        background: linear-gradient(180deg, #6D4C41 0%, #5D4037 100%);
        border-radius: 3px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.3), inset 0 1px 0 rgba(255,255,255,0.1), inset 0 -2px 5px rgba(0,0,0,0.3);
    }
    .shelf-board::before {
        content: '';
        position: absolute;
        bottom: -8px; left: 0; right: 0;
        height: 8px;
        background: linear-gradient(180deg, transparent, rgba(0,0,0,0.15));
        border-radius: 0 0 3px 3px;
    }

    .fav-books-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 20px;
        position: relative;
        z-index: 1;
        min-height: 180px;
    }

    .fav-book-item {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 6px 15px rgba(0,0,0,0.25);
        transition: all 0.3s;
        cursor: pointer;
        aspect-ratio: 2/3;
        position: relative;
    }
    .fav-book-item:hover {
        transform: translateY(-10px) rotate(2deg);
        box-shadow: 0 12px 25px rgba(0,0,0,0.35);
    }

    .fav-cover-only {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        overflow: hidden;
    }
    .fav-cover-only img { width: 100%; height: 100%; object-fit: cover; }
    .fav-cover-only i { font-size: 50px; color: rgba(255,255,255,0.4); }

    /* Heart button - always active (merah) di favorit page */
    .fav-heart-btn {
        position: absolute;
        top: 6px; right: 6px;
        width: 28px; height: 28px;
        background: white;
        border: none;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        z-index: 5;
        box-shadow: 0 2px 6px rgba(0,0,0,0.25);
        transition: all 0.2s;
        font-size: 0.72rem;
        color: #E53935;
    }
    .fav-heart-btn:hover { transform: scale(1.15); }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        color: rgba(255,255,255,0.7);
    }
    .empty-state i { font-size: 70px; margin-bottom: 20px; opacity: 0.5; display: block; }
    .empty-state h3 { font-size: 1.5rem; color: white; margin-bottom: 10px; }
    .empty-state a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 20px;
        padding: 12px 24px;
        background: rgba(255,255,255,0.2);
        color: white;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: background 0.2s;
    }
    .empty-state a:hover { background: rgba(255,255,255,0.3); }

    @media (max-width: 768px) {
        .fav-books-grid { grid-template-columns: repeat(3, 1fr); gap: 12px; }
        .bookshelf-wrapper { padding: 25px 15px; }
    }
    @media (max-width: 480px) {
        .fav-books-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .bookshelf-wrapper { padding: 15px 10px; }
    }
</style>

<div class="section-head">
    <h2>Buku Favorit Saya</h2>
</div>

<div class="bookshelf-wrapper">
    @php
        $colors = [
            'linear-gradient(135deg, #A1887F, #8D6E63)',
            'linear-gradient(135deg, #7986CB, #5C6BC0)',
            'linear-gradient(135deg, #81C784, #66BB6A)',
            'linear-gradient(135deg, #FFB74D, #FFA726)',
            'linear-gradient(135deg, #E57373, #EF5350)',
            'linear-gradient(135deg, #9575CD, #7E57C2)',
        ];
        $favArray = $favorites->all();
        $chunked = array_chunk($favArray ?: [], 5);
        if (empty($chunked)) $chunked = [[]];
    @endphp

    @if($favorites->count() > 0)
        @foreach($chunked as $row)
            <div class="shelf-row">
                <div class="fav-books-grid">
                    @foreach($row as $i => $f)
                        <div class="fav-book-item">
                            <button class="fav-heart-btn" onclick="event.stopPropagation(); removeFav(this, {{ $f->book->id }})" title="Hapus dari favorit">
                                <i class="fas fa-heart"></i>
                            </button>
                            <div class="fav-cover-only" style="background: {{ $colors[$i % 6] }};">
                                @if($f->book->cover_image)
                                    <img src="{{ asset('img/covers/' . $f->book->cover_image) }}" alt="{{ $f->book->title }}">
                                @else
                                    <i class="fas fa-book"></i>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="shelf-board"></div>
            </div>
        @endforeach
    @else
        <div class="empty-state">
            <i class="fas fa-heart"></i>
            <h3>Belum ada buku favorit</h3>
            <p>Tandai buku favorit Anda dari koleksi buku</p>
            <a href="{{ route('user.books') }}"><i class="fas fa-book"></i> Lihat Koleksi Buku</a>
        </div>
    @endif
</div>

@push('scripts')
<script>
function removeFav(btn, bookId) {
    fetch(`/favorit/${bookId}/remove`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Hapus card dari DOM dengan animasi
            const card = btn.closest('.fav-book-item');
            card.style.transition = 'all 0.3s';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.8)';
            setTimeout(() => { card.remove(); location.reload(); }, 300);
        }
    })
    .catch(() => {});
}
</script>
@endpush
@endsection