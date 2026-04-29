{{-- Component: Book Grid - Cover Only with Modal --}}
@props(['books', 'showSearch' => true])

<style>
    /* Search Section */
    .search-section {
        margin-bottom: 30px;
    }

    .search-box {
        position: relative;
        max-width: 100%;
    }

    .search-box input {
        width: 100%;
        padding: 15px 60px 15px 20px;
        border: none;
        border-radius: 50px;
        font-size: 0.95rem;
        font-family: 'Poppins', sans-serif;
        background: rgba(255, 255, 255, 0.9);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .search-box input:focus {
        outline: none;
        background: white;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }

    .search-box button {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        background: linear-gradient(135deg, var(--wood-medium), var(--wood-dark));
        border: none;
        color: white;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .search-box button:hover {
        transform: translateY(-50%) scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    /* Bookshelf Container */
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
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: repeating-linear-gradient(
            90deg,
            transparent,
            transparent 2px,
            rgba(0,0,0,0.03) 2px,
            rgba(0,0,0,0.03) 4px
        );
        border-radius: 20px;
        pointer-events: none;
    }

    .shelf-row {
        position: relative;
        margin-bottom: 50px;
    }

    .shelf-row:last-child {
        margin-bottom: 0;
    }

    .shelf-board {
        position: absolute;
        bottom: -25px;
        left: -20px;
        right: -20px;
        height: 15px;
        background: linear-gradient(180deg, #6D4C41 0%, #5D4037 100%);
        border-radius: 3px;
        box-shadow: 
            0 4px 8px rgba(0,0,0,0.3),
            inset 0 1px 0 rgba(255,255,255,0.1),
            inset 0 -2px 5px rgba(0,0,0,0.3);
    }

    .shelf-board::before {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        right: 0;
        height: 8px;
        background: linear-gradient(180deg, transparent, rgba(0,0,0,0.15));
        border-radius: 0 0 3px 3px;
    }

    .books-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 20px;
        position: relative;
        z-index: 1;
        min-height: 220px;
    }

    .book-item {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 6px 15px rgba(0,0,0,0.25);
        transition: all 0.3s;
        cursor: pointer;
        aspect-ratio: 2/3;
    }

    .book-item:hover {
        transform: translateY(-10px) rotate(2deg);
        box-shadow: 0 12px 25px rgba(0,0,0,0.35);
    }

    .book-cover-only {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #A1887F, #8D6E63);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .book-cover-only img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .book-cover-only i {
        font-size: 60px;
        color: rgba(255,255,255,0.4);
    }

    /* IMPROVED MODAL STYLES */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.75);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        padding: 20px;
        backdrop-filter: blur(4px);
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: linear-gradient(135deg, #FFF8E1 0%, #FFFFFF 100%);
        border-radius: 24px;
        max-width: 700px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        box-shadow: 0 20px 60px rgba(0,0,0,0.4);
        animation: modalSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-30px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .modal-close {
        position: absolute;
        top: 16px;
        right: 16px;
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.95);
        border: none;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 12px rgba(0,0,0,0.15);
        transition: all 0.3s;
        z-index: 10;
    }

    .modal-close:hover {
        background: #E53935;
        color: white;
        transform: rotate(90deg) scale(1.1);
    }

    .modal-close i {
        font-size: 18px;
    }

    .modal-body {
        padding: 32px;
    }

    .modal-header {
        display: flex;
        align-items: flex-start;
        gap: 24px;
        margin-bottom: 24px;
        padding-bottom: 20px;
        border-bottom: 2px solid rgba(141, 110, 99, 0.1);
    }

    .modal-cover-small {
        flex-shrink: 0;
        width: 120px;
        height: 180px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        background: linear-gradient(135deg, #A1887F, #8D6E63);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-cover-small img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .modal-cover-small i {
        font-size: 48px;
        color: rgba(255,255,255,0.3);
    }

    .modal-title-section {
        flex: 1;
    }

    .modal-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.75rem;
        color: var(--wood-dark);
        margin: 0 0 8px 0;
        line-height: 1.3;
        font-weight: 700;
    }

    .modal-author {
        font-style: italic;
        color: var(--wood-medium);
        font-size: 1.05rem;
        margin: 0;
        font-weight: 500;
    }

    .modal-details-grid {
        display: grid;
        gap: 14px;
        margin-bottom: 20px;
    }

    .detail-row {
        display: grid;
        grid-template-columns: 140px 1fr;
        gap: 16px;
        padding: 10px 0;
    }

    .detail-label {
        font-weight: 600;
        color: var(--wood-dark);
        font-size: 0.9rem;
    }

    .detail-value {
        color: var(--text-gray);
        font-size: 0.9rem;
    }

    .stock-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .stock-badge.available {
        background: rgba(129, 199, 132, 0.15);
        color: #2E7D32;
    }

    .stock-badge.unavailable {
        background: rgba(229, 115, 115, 0.15);
        color: #C62828;
    }

    .stock-badge i {
        font-size: 6px;
    }

    .modal-description {
        margin-top: 16px;
        padding: 18px;
        background: rgba(255, 255, 255, 0.7);
        border-radius: 12px;
        border-left: 4px solid var(--wood-medium);
    }

    .modal-description h4 {
        margin: 0 0 10px 0;
        color: var(--wood-dark);
        font-size: 1rem;
        font-weight: 600;
    }

    .modal-description p {
        margin: 0;
        line-height: 1.7;
        color: var(--text-gray);
        font-size: 0.9rem;
    }

    .modal-actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 2px solid rgba(141, 110, 99, 0.1);
    }

    .modal-action {
        flex: 1;
        padding: 13px 20px;
        border: none;
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-borrow {
        background: linear-gradient(135deg, #66BB6A, #43A047);
        color: white;
        box-shadow: 0 4px 12px rgba(102, 187, 106, 0.3);
    }

    .btn-borrow:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(102, 187, 106, 0.4);
    }

    .btn-borrow:disabled {
        background: #BDBDBD;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .btn-edit {
        background: linear-gradient(135deg, #FFA726, #FB8C00);
        color: white;
        box-shadow: 0 4px 12px rgba(255, 167, 38, 0.3);
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(255, 167, 38, 0.4);
    }

    .btn-delete {
        background: linear-gradient(135deg, #EF5350, #E53935);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 83, 80, 0.3);
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(239, 83, 80, 0.4);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        color: rgba(255,255,255,0.7);
    }

    .empty-state i {
        font-size: 80px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        margin: 0 0 10px 0;
        color: white;
    }

    .empty-state p {
        margin: 0;
        font-size: 1rem;
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .pagination-info {
        color: white;
        font-weight: 500;
        background: rgba(0,0,0,0.2);
        padding: 10px 20px;
        border-radius: 8px;
    }

    .pagination-controls {
        display: flex;
        gap: 8px;
    }

    .pagination-controls a,
    .pagination-controls span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: white;
        color: var(--wood-dark);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .pagination-controls a:hover {
        background: var(--wood-medium);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .pagination-controls span.active {
        background: linear-gradient(135deg, var(--wood-medium), var(--wood-dark));
        color: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .pagination-controls span.disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    @media (max-width: 1200px) {
        .books-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width: 768px) {
        .books-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .pagination-wrapper {
            flex-direction: column;
            gap: 15px;
        }

        .bookshelf-wrapper {
            padding: 20px;
        }

        .modal-header {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .modal-cover-small {
            width: 140px;
            height: 210px;
        }

        .detail-row {
            grid-template-columns: 1fr;
            gap: 4px;
        }

        .modal-actions {
            flex-direction: column;
        }
    }
</style>

{{-- Search Bar --}}
@if($showSearch)
<div class="search-section">
    <form action="" method="GET" class="search-box">
        <input type="text" 
               name="search" 
               placeholder="Cari judul buku, penulis, atau ISBN..." 
               value="{{ request('search') }}">
        <button type="submit">
            <i class="fas fa-search"></i>
        </button>
    </form>
</div>
@endif

{{-- Bookshelf - ALWAYS 4 ROWS --}}
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
        
        $booksArray = $books->items();
        $totalSlots = 24;
        
        $displayBooks = [];
        for ($i = 0; $i < $totalSlots; $i++) {
            $displayBooks[] = $booksArray[$i] ?? null;
        }
        
        $chunkedBooks = array_chunk($displayBooks, 6);
    @endphp

    @if(count($booksArray) > 0 || $showSearch)
        @foreach($chunkedBooks as $rowIndex => $rowBooks)
            <div class="shelf-row">
                <div class="books-grid">
                    @foreach($rowBooks as $index => $book)
                        @if($book)
                            <div class="book-item" onclick="openBookModal({{ $book->id }})">
                                <div class="book-cover-only" style="background: {{ $colors[$index % 6] }};">
                                    @if($book->cover_image)
                                        <img src="{{ asset('img/covers/' . $book->cover_image) }}" alt="{{ $book->title }}">
                                    @else
                                        <i class="fas fa-book"></i>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div style="visibility: hidden;"></div>
                        @endif
                    @endforeach
                </div>
                
                <div class="shelf-board"></div>
            </div>
        @endforeach

        @if($books->hasPages())
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    Page {{ $books->currentPage() }} of {{ $books->lastPage() }}
                </div>
                
                <div class="pagination-controls">
                    @if($books->onFirstPage())
                        <span class="disabled">&lt;</span>
                    @else
                        <a href="{{ $books->previousPageUrl() }}">&lt;</a>
                    @endif

                    @foreach(range(1, $books->lastPage()) as $page)
                        @if($page == $books->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $books->url($page) }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($books->hasMorePages())
                        <a href="{{ $books->nextPageUrl() }}">&gt;</a>
                    @else
                        <span class="disabled">&gt;</span>
                    @endif
                </div>
            </div>
        @endif
    @else
        <div class="empty-state">
            <i class="fas fa-book"></i>
            <h3>Tidak ada buku ditemukan</h3>
            <p>{{ request('search') ? 'Coba kata kunci lain' : 'Belum ada buku di perpustakaan' }}</p>
        </div>
    @endif
</div>

{{-- Modal --}}
<div class="modal-overlay" id="bookModal" onclick="closeModalOnOverlay(event)">
    <div class="modal-content">
        <button class="modal-close" onclick="closeBookModal()">
            <i class="fas fa-times"></i>
        </button>
        
        <div class="modal-body" id="modalBody">
            <div style="text-align: center; padding: 40px;">
                <i class="fas fa-spinner fa-spin" style="font-size: 40px; color: var(--wood-medium);"></i>
                <p style="margin-top: 20px; color: var(--wood-medium);">Memuat detail buku...</p>
            </div>
        </div>
    </div>
</div>

<script>
const API_BASE_URL = '{{ url('/') }}';

function openBookModal(bookId) {
    const modal = document.getElementById('bookModal');
    const modalBody = document.getElementById('modalBody');
    
    modalBody.innerHTML = `
        <div style="text-align: center; padding: 40px;">
            <i class="fas fa-spinner fa-spin" style="font-size: 40px; color: var(--wood-medium);"></i>
            <p style="margin-top: 20px; color: var(--wood-medium);">Memuat detail buku...</p>
        </div>
    `;
    
    modal.classList.add('active');
    
    fetch(`${API_BASE_URL}/api/books/${bookId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(book => {
            const isAdmin = {{ Auth::check() && Auth::user()->role === 'admin' ? 'true' : 'false' }};
            
            modalBody.innerHTML = `
                <div class="modal-header">
                    <div class="modal-cover-small">
                        ${book.cover_image 
                            ? `<img src="${API_BASE_URL}/img/covers/${book.cover_image}" alt="${escapeHtml(book.title)}">`
                            : `<i class="fas fa-book"></i>`
                        }
                    </div>
                    <div class="modal-title-section">
                        <h2 class="modal-title">${escapeHtml(book.title)}</h2>
                        <p class="modal-author">oleh ${escapeHtml(book.author)}</p>
                    </div>
                </div>
                
                <div class="modal-details-grid">
                    <div class="detail-row">
                        <span class="detail-label">ISBN</span>
                        <span class="detail-value">${escapeHtml(book.isbn) || '-'}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Penerbit</span>
                        <span class="detail-value">${escapeHtml(book.publisher) || '-'}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Tahun Terbit</span>
                        <span class="detail-value">${book.publication_year || '-'}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Ketersediaan</span>
                        <span class="detail-value">
                            <span class="stock-badge ${book.available > 0 ? 'available' : 'unavailable'}">
                                <i class="fas fa-circle"></i>
                                ${book.available} dari ${book.stock} tersedia
                            </span>
                        </span>
                    </div>
                </div>
                
                ${book.description ? `
                    <div class="modal-description">
                        <h4>Deskripsi</h4>
                        <p>${escapeHtml(book.description)}</p>
                    </div>
                ` : ''}
                
                <div class="modal-actions">
                    ${isAdmin 
                        ? `
                            <a href="${API_BASE_URL}/admin/books/${book.id}/edit" class="modal-action btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button class="modal-action btn-delete" onclick="deleteBook(${book.id})">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        `
                        : `
                            <button class="modal-action btn-borrow" 
                                ${book.available <= 0 ? 'disabled' : ''}
                                onclick="borrowBook(${book.id})">
                                <i class="fas fa-book-reader"></i> 
                                ${book.available > 0 ? 'Pinjam Buku' : 'Stok Habis'}
                            </button>
                        `
                    }
                </div>
            `;
        })
        .catch(error => {
            console.error('Error fetching book details:', error);
            modalBody.innerHTML = `
                <div style="text-align: center; padding: 40px;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 40px; color: #E57373;"></i>
                    <h3 style="color: var(--wood-dark); margin: 20px 0 10px;">Gagal Memuat Detail Buku</h3>
                    <p style="color: var(--wood-medium);">Terjadi kesalahan saat mengambil data buku.</p>
                    <p style="color: var(--text-gray); font-size: 0.9rem; margin-top: 10px;">Error: ${error.message}</p>
                    <button onclick="closeBookModal()" style="margin-top: 20px; padding: 10px 20px; background: var(--wood-medium); color: white; border: none; border-radius: 8px; cursor: pointer;">
                        Tutup
                    </button>
                </div>
            `;
        });
}

function closeBookModal() {
    document.getElementById('bookModal').classList.remove('active');
}

function closeModalOnOverlay(event) {
    if (event.target.id === 'bookModal') {
        closeBookModal();
    }
}

// Function untuk borrow book
function borrowBook(bookId) {
    if(typeof Swal !== 'undefined') {
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
    } else {
        // Fallback jika SweetAlert belum dimuat
        if(confirm('Pinjam buku ini? Harus dikembalikan dalam 14 hari')) {
            fetch(`/pinjam/${bookId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(r => r.json())
            .then(data => {
                alert(data.message);
                if(data.success) {
                    closeBookModal();
                    location.reload();
                }
            })
            .catch(() => alert('Terjadi kesalahan'));
        }
    }
}

function deleteBook(bookId) {
    if (confirm('Apakah Anda yakin ingin menghapus buku ini? Data yang sudah dihapus tidak dapat dikembalikan!')) {
        // Create form for DELETE request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `${API_BASE_URL}/admin/books/${bookId}`;
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add DELETE method
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function escapeHtml(text) {
    if (!text) return '';
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeBookModal();
    }
});
</script>