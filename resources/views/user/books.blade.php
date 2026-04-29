@extends('layouts.app-navbar')

@section('title', 'Koleksi Buku - Digishelf')

@section('navbar-menu')
    <li><a href="{{ route('user.dashboard') }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
    <li><a href="{{ route('user.books') }}" class="active"><i class="fas fa-book"></i> Koleksi Buku</a></li>
    <li><a href="{{ route('user.borrowings') }}"><i class="fas fa-book-reader"></i> Peminjaman</a></li>
    <li><a href="{{ route('user.history') }}"><i class="fas fa-history"></i> Riwayat</a></li>
    <li><a href="{{ route('user.favorites') }}"><i class="fas fa-heart"></i> Favorit</a></li>
    <li><a href="{{ route('user.account') }}"><i class="fas fa-user-circle"></i> Akun</a></li>
@endsection

@section('content')
<style>
    .search-section {
        background: white;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    .search-container {
        position: relative;
        max-width: 600px;
    }
    .search-icon {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--wood-medium);
        font-size: 1.2rem;
    }
    .search-input {
        width: 100%;
        padding: 15px 20px 15px 55px;
        border: 2px solid var(--cream);
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s;
    }
    .search-input:focus {
        outline: none;
        border-color: var(--wood-medium);
        box-shadow: 0 0 0 4px rgba(141,110,99,0.1);
    }
    .search-loading {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        display: none;
    }
    .search-loading.show {
        display: block;
    }
    
    .books-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 25px;
    }
    .book-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s;
        cursor: pointer;
    }
    .book-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .book-cover {
        width: 100%;
        height: 280px;
        background: linear-gradient(135deg, #A1887F, #8D6E63);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .book-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .book-cover i {
        font-size: 64px;
        color: rgba(255,255,255,0.5);
    }
    .book-info {
        padding: 20px;
    }
    .book-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 8px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    .book-author {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 10px;
    }
    .book-stock {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .stock-available {
        background: #E8F5E9;
        color: #2E7D32;
    }
    .stock-unavailable {
        background: #FFEBEE;
        color: #C62828;
    }
    
    .no-results {
        text-align: center;
        padding: 80px 20px;
        color: #999;
    }
    .no-results i {
        font-size: 80px;
        margin-bottom: 25px;
        opacity: 0.4;
    }
    .no-results h3 {
        font-family: 'Crimson Pro', serif;
        font-size: 1.8rem;
        color: var(--text-dark);
        margin-bottom: 10px;
    }
    .no-results p {
        font-size: 1.1rem;
    }
    
    /* Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.7);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .modal-overlay.active {
        display: flex;
    }
    .modal-content {
        background: white;
        border-radius: 20px;
        max-width: 700px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
    }
    .modal-close {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(0,0,0,0.1);
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 20px;
        transition: all 0.3s;
        z-index: 10;
    }
    .modal-close:hover {
        background: rgba(0,0,0,0.2);
        transform: rotate(90deg);
    }
    .modal-book-cover {
        width: 100%;
        height: 400px;
        background: linear-gradient(135deg, #A1887F, #8D6E63);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .modal-book-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .modal-book-info {
        padding: 35px;
    }
    .modal-book-title {
        font-family: 'Crimson Pro', serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 10px;
    }
    .modal-book-author {
        font-size: 1.1rem;
        color: #666;
        margin-bottom: 20px;
    }
    .modal-book-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }
    .meta-item {
        background: var(--cream);
        padding: 15px;
        border-radius: 10px;
    }
    .meta-label {
        font-size: 0.8rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }
    .meta-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-dark);
    }
    .modal-description {
        margin-bottom: 25px;
    }
    .modal-description h4 {
        font-size: 1.1rem;
        margin-bottom: 10px;
        color: var(--text-dark);
    }
    .modal-description p {
        line-height: 1.7;
        color: #666;
    }
    .btn-borrow {
        width: 100%;
        background: linear-gradient(135deg, #66BB6A, #43A047);
        color: white;
        padding: 15px;
        border: none;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    .btn-borrow:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(67,160,71,0.4);
    }
    .btn-borrow:disabled {
        background: #999;
        cursor: not-allowed;
        transform: none;
    }
    
    @media (max-width: 768px) {
        .books-grid {
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 15px;
        }
        .book-cover {
            height: 220px;
        }
        .modal-book-cover {
            height: 300px;
        }
        .modal-book-info {
            padding: 25px;
        }
    }
</style>

<div class="search-section">
    <div class="search-container">
        <i class="fas fa-search search-icon"></i>
        <input 
            type="text" 
            class="search-input" 
            id="searchInput" 
            placeholder="Cari judul, penulis, atau ISBN..."
            autocomplete="off"
        >
        <div class="search-loading" id="searchLoading">
            <i class="fas fa-spinner fa-spin"></i>
        </div>
    </div>
</div>

<div id="booksContainer">
    @if($books->count() > 0)
        <div class="books-grid">
            @foreach($books as $book)
            <div class="book-card" onclick="openBookModal({{ $book->id }})">
                <div class="book-cover">
                    @if($book->cover_image)
                        <img src="{{ asset('img/covers/' . $book->cover_image) }}" alt="{{ $book->title }}">
                    @else
                        <i class="fas fa-book"></i>
                    @endif
                </div>
                <div class="book-info">
                    <div class="book-title">{{ $book->title }}</div>
                    <div class="book-author">{{ $book->author }}</div>
                    <span class="book-stock {{ $book->available > 0 ? 'stock-available' : 'stock-unavailable' }}">
                        {{ $book->available > 0 ? 'Tersedia' : 'Tidak Tersedia' }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="no-results">
            <i class="fas fa-book"></i>
            <h3>Belum Ada Buku</h3>
            <p>Koleksi buku masih kosong</p>
        </div>
    @endif
</div>

<!-- Modal -->
<div class="modal-overlay" id="bookModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeBookModal()">&times;</button>
        <div id="modalContent">
            <div style="padding: 60px; text-align: center;">
                <i class="fas fa-spinner fa-spin" style="font-size: 48px; color: var(--wood-medium);"></i>
                <p style="margin-top: 20px; color: #666;">Memuat detail buku...</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let searchTimeout;
const searchInput = document.getElementById('searchInput');
const searchLoading = document.getElementById('searchLoading');
const booksContainer = document.getElementById('booksContainer');

// AJAX Search
searchInput.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const query = this.value.trim();
    
    searchLoading.classList.add('show');
    
    searchTimeout = setTimeout(() => {
        fetch(`{{ route('user.books') }}?search=${encodeURIComponent(query)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => r.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.getElementById('booksContainer');
            if (newContent) {
                booksContainer.innerHTML = newContent.innerHTML;
            }
            searchLoading.classList.remove('show');
        })
        .catch(() => {
            searchLoading.classList.remove('show');
        });
    }, 500);
});

// Modal Functions
function openBookModal(bookId) {
    const modal = document.getElementById('bookModal');
    const modalContent = document.getElementById('modalContent');
    
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    
    fetch(`/api/books/${bookId}`)
        .then(r => r.json())
        .then(book => {
            modalContent.innerHTML = `
                <div class="modal-book-cover">
                    ${book.cover_image ? 
                        `<img src="/img/covers/${book.cover_image}" alt="${book.title}">` : 
                        '<i class="fas fa-book" style="font-size:80px;color:rgba(255,255,255,0.5)"></i>'
                    }
                </div>
                <div class="modal-book-info">
                    <h2 class="modal-book-title">${book.title}</h2>
                    <div class="modal-book-author">${book.author}</div>
                    
                    <div class="modal-book-meta">
                        <div class="meta-item">
                            <div class="meta-label">ISBN</div>
                            <div class="meta-value">${book.isbn || '-'}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Penerbit</div>
                            <div class="meta-value">${book.publisher || '-'}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Tahun</div>
                            <div class="meta-value">${book.publication_year || '-'}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Stok Tersedia</div>
                            <div class="meta-value">${book.available}/${book.stock}</div>
                        </div>
                    </div>
                    
                    ${book.description ? `
                        <div class="modal-description">
                            <h4>Deskripsi</h4>
                            <p>${book.description}</p>
                        </div>
                    ` : ''}
                    
                    <button 
                        class="btn-borrow" 
                        onclick="borrowBook(${book.id})"
                        ${book.available <= 0 ? 'disabled' : ''}
                    >
                        <i class="fas fa-book-reader"></i> 
                        ${book.available > 0 ? 'Pinjam Buku' : 'Stok Habis'}
                    </button>
                </div>
            `;
        })
        .catch(() => {
            modalContent.innerHTML = `
                <div style="padding: 60px; text-align: center;">
                    <i class="fas fa-exclamation-circle" style="font-size: 48px; color: #C62828;"></i>
                    <p style="margin-top: 20px; color: #666;">Gagal memuat detail buku</p>
                </div>
            `;
        });
}

function closeBookModal() {
    document.getElementById('bookModal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

function borrowBook(bookId) {
    Swal.fire({
        title: 'Pinjam Buku?',
        text: 'Buku harus dikembalikan dalam 14 hari',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#66BB6A',
        cancelButtonColor: '#E0E0E0',
        confirmButtonText: 'Ya, Pinjam',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/pinjam/${bookId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(r => r.json())
            .then(data => {
                if(data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#66BB6A'
                    }).then(() => {
                        closeBookModal();
                        location.reload();
                    });
                } else {
                    Swal.fire('Gagal', data.message, 'error');
                }
            })
            .catch(() => {
                Swal.fire('Error', 'Terjadi kesalahan', 'error');
            });
        }
    });
}

// Close modal with Escape
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeBookModal();
});

// Close modal when clicking outside
document.getElementById('bookModal').addEventListener('click', function(e) {
    if (e.target === this) closeBookModal();
});
</script>
@endpush
@endsection