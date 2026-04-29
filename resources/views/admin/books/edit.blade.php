@extends('layouts.app')

@section('sidebar-menu')
    <li>
        <a href="{{ route('admin.dashboard') }}">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.books.index') }}" class="active">
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
    :root {
        --wood-dark: #5D4037;
        --wood-medium: #8D6E63;
        --wood-light: #D7CCC8;
        --cream: #FFF8E1;
        --text-gray: #6D6D6D;
    }

    .container {
        max-width: 900px;
        margin: 0 auto;
        padding: 30px;
    }

    .page-header {
        margin-bottom: 40px;
    }

    .page-header h1 {
        font-family: 'Playfair Display', serif;
        color: var(--wood-dark);
        font-size: 2.5rem;
        margin-bottom: 10px;
    }

    .page-header p {
        color: var(--text-gray);
        font-size: 1.1rem;
    }

    .form-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: var(--wood-dark);
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .form-group label.required::after {
        content: ' *';
        color: #E53935;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #E0E0E0;
        border-radius: 10px;
        font-size: 0.95rem;
        font-family: 'Poppins', sans-serif;
        transition: all 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--wood-medium);
        box-shadow: 0 0 0 3px rgba(141, 110, 99, 0.1);
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    /* Cover Upload Section */
    .cover-upload-section {
        margin-bottom: 25px;
    }

    .upload-area {
        border: 2px dashed #BDBDBD;
        border-radius: 12px;
        padding: 40px;
        text-align: center;
        background: #FAFAFA;
        cursor: pointer;
        transition: all 0.3s;
    }

    .upload-area:hover {
        border-color: var(--wood-medium);
        background: #F5F5F5;
    }

    .upload-area i {
        font-size: 48px;
        color: var(--wood-medium);
        margin-bottom: 15px;
    }

    .upload-area p {
        margin: 5px 0;
        color: var(--text-gray);
    }

    .upload-area p.upload-title {
        font-weight: 600;
        color: var(--wood-dark);
        font-size: 1.1rem;
        margin-bottom: 8px;
    }

    .upload-area p.upload-hint {
        font-size: 0.85rem;
        color: #9E9E9E;
    }

    /* Preview Cover */
    .cover-preview {
        display: none;
        position: relative;
        max-width: 300px;
        margin: 0 auto;
    }

    .cover-preview.active {
        display: block;
    }

    .preview-image-wrapper {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    }

    .preview-image-wrapper img {
        width: 100%;
        height: auto;
        display: block;
    }

    .remove-cover {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 36px;
        height: 36px;
        background: #E53935;
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        transition: all 0.3s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        z-index: 10;
    }

    .remove-cover:hover {
        background: #C62828;
        transform: scale(1.1);
    }

    .preview-filename {
        margin-top: 12px;
        text-align: center;
        color: var(--text-gray);
        font-size: 0.9rem;
        word-break: break-all;
    }

    /* Hidden file input */
    #cover_image {
        display: none;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 35px;
        padding-top: 25px;
        border-top: 2px solid #F0F0F0;
    }

    .btn {
        padding: 14px 32px;
        border: none;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--wood-medium), var(--wood-dark));
        color: white;
        flex: 1;
        justify-content: center;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(93, 64, 55, 0.3);
    }

    .btn-secondary {
        background: white;
        color: var(--wood-dark);
        border: 2px solid var(--wood-light);
    }

    .btn-secondary:hover {
        background: var(--wood-light);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="container">
    <div class="page-header">
        <h1>Edit Buku</h1>
        <p>Edit informasi buku</p>
    </div>

    <div class="form-card">
        <form action="{{ route('admin.books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title" class="required">Judul Buku</label>
                <input type="text" 
                       class="form-control @error('title') is-invalid @enderror" 
                       id="title" 
                       name="title" 
                       value="{{ old('title', $book->title) }}" 
                       placeholder="Masukkan judul buku"
                       required>
                @error('title')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="author" class="required">Penulis</label>
                <input type="text" 
                       class="form-control @error('author') is-invalid @enderror" 
                       id="author" 
                       name="author" 
                       value="{{ old('author', $book->author) }}" 
                       placeholder="Masukkan nama penulis"
                       required>
                @error('author')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="isbn">ISBN</label>
                    <input type="text" 
                           class="form-control @error('isbn') is-invalid @enderror" 
                           id="isbn" 
                           name="isbn" 
                           value="{{ old('isbn', $book->isbn) }}" 
                           placeholder="978-xxx-xxxx">
                    @error('isbn')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="publication_year">Tahun Terbit</label>
                    <input type="number" 
                           class="form-control @error('publication_year') is-invalid @enderror" 
                           id="publication_year" 
                           name="publication_year" 
                           value="{{ old('publication_year', $book->publication_year) }}" 
                           placeholder="2024"
                           min="1900"
                           max="{{ date('Y') + 1 }}">
                    @error('publication_year')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="publisher">Penerbit</label>
                <input type="text" 
                       class="form-control @error('publisher') is-invalid @enderror" 
                       id="publisher" 
                       name="publisher" 
                       value="{{ old('publisher', $book->publisher) }}" 
                       placeholder="Masukkan nama penerbit">
                @error('publisher')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="stock" class="required">Jumlah Stok</label>
                <input type="number" 
                       class="form-control @error('stock') is-invalid @enderror" 
                       id="stock" 
                       name="stock" 
                       value="{{ old('stock', $book->stock) }}" 
                       placeholder="Jumlah buku yang tersedia"
                       min="0"
                       required>
                @error('stock')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" 
                          name="description" 
                          placeholder="Masukkan deskripsi buku (opsional)">{{ old('description', $book->description) }}</textarea>
                @error('description')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="cover-upload-section">
                <label>Cover Buku</label>
                
                <!-- Upload Area (shown when no image) -->
                <div class="upload-area" id="uploadArea" onclick="document.getElementById('cover_image').click()" style="{{ $book->cover_image ? 'display: none;' : '' }}">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p class="upload-title">Klik untuk upload cover buku</p>
                    <p>Format: JPG, PNG, WEBP, AVIF (Max: 2MB)</p>
                </div>

                <!-- Preview Area (shown when image selected or exists) -->
                <div class="cover-preview {{ $book->cover_image ? 'active' : '' }}" id="coverPreview">
                    <div class="preview-image-wrapper">
                        <button type="button" class="remove-cover" onclick="removeCover()">
                            <i class="fas fa-times"></i>
                        </button>
                        <img id="previewImage" 
                             src="{{ $book->cover_image ? asset('img/covers/' . $book->cover_image) : '' }}" 
                             alt="Preview">
                    </div>
                    <p class="preview-filename" id="previewFilename">{{ $book->cover_image ?? '' }}</p>
                </div>

                <input type="file" 
                       id="cover_image" 
                       name="cover_image" 
                       accept=".jpg,.jpeg,.png,.webp,.avif"
                       onchange="previewCover(event)">
                
                <!-- Hidden input to mark cover for deletion -->
                <input type="hidden" id="remove_cover_flag" name="remove_cover" value="0">
                
                @error('cover_image')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Update Buku
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const hasExistingCover = {{ $book->cover_image ? 'true' : 'false' }};

function previewCover(event) {
    const file = event.target.files[0];
    
    if (file) {
        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 2MB.');
            event.target.value = '';
            return;
        }

        // Validate file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/avif'];
        if (!validTypes.includes(file.type)) {
            alert('Format file tidak didukung! Gunakan JPG, PNG, WEBP, atau AVIF.');
            event.target.value = '';
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
            document.getElementById('previewFilename').textContent = file.name;
            document.getElementById('uploadArea').style.display = 'none';
            document.getElementById('coverPreview').classList.add('active');
            document.getElementById('remove_cover_flag').value = '0';
        };
        reader.readAsDataURL(file);
    }
}

function removeCover() {
    // Clear file input
    document.getElementById('cover_image').value = '';
    
    // Hide preview and show upload area
    document.getElementById('coverPreview').classList.remove('active');
    document.getElementById('uploadArea').style.display = 'block';
    
    // Clear preview image
    document.getElementById('previewImage').src = '';
    document.getElementById('previewFilename').textContent = '';
    
    // Mark cover for deletion if it was an existing cover
    if (hasExistingCover) {
        document.getElementById('remove_cover_flag').value = '1';
    }
}
</script>
@endsection