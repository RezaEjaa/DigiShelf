@extends('layouts.app')

@section('title', 'Akun - Digishelf')
@section('page-title', 'Akun')
@section('page-subtitle', 'Kelola informasi akun Anda')

@section('sidebar-menu')
    <li><a href="{{ route('user.dashboard') }}"><i class="fas fa-th-large"></i><span>Dashboard</span></a></li>
    <li><a href="{{ route('user.books') }}"><i class="fas fa-book"></i><span>Koleksi Buku</span></a></li>
    <li><a href="{{ route('user.borrowings') }}"><i class="fas fa-book-reader"></i><span>Peminjaman</span></a></li>
    <li><a href="{{ route('user.history') }}"><i class="fas fa-history"></i><span>Riwayat Peminjaman</span></a></li>
    <li><a href="{{ route('user.favorites') }}"><i class="fas fa-heart"></i><span>Favorit Saya</span></a></li>
    <li class="logout-section"><a href="{{ route('user.account') }}" class="active"><i class="fas fa-user-circle"></i><span>Akun</span></a></li>
    <li><form action="{{ route('logout') }}" method="POST" class="logout-form">@csrf<button type="submit"><i class="fas fa-sign-out-alt"></i><span>Logout</span></button></form></li>
@endsection

@section('content')
<style>
.account-container{max-width:600px;margin:0 auto;background:white;border-radius:20px;padding:50px;box-shadow:0 10px 40px rgba(0,0,0,0.1);text-align:center}
.profile-photo{width:150px;height:150px;border-radius:50%;background:linear-gradient(135deg,var(--wood-medium),var(--wood-dark));display:flex;align-items:center;justify-content:center;margin:0 auto 30px;box-shadow:0 8px 20px rgba(0,0,0,0.15);position:relative}
.profile-photo i{font-size:80px;color:white}
.profile-initials{font-size:60px;font-weight:700;color:white;font-family:'Crimson Pro',serif}
.profile-info{margin-bottom:40px}
.info-item{background:var(--cream);padding:20px;border-radius:12px;margin-bottom:15px;text-align:left;display:flex;justify-content:space-between;align-items:center}
.info-label{font-weight:600;color:var(--wood-dark);font-size:0.9rem;text-transform:uppercase;letter-spacing:0.5px}
.info-value{font-size:1.1rem;color:var(--text-dark);font-weight:500}
.password-value{font-size:1.5rem;letter-spacing:3px;color:#999}
.action-buttons{display:flex;gap:15px;justify-content:center;flex-wrap:wrap}
.btn{padding:15px 35px;border-radius:12px;border:none;cursor:pointer;font-weight:600;font-size:1rem;transition:all 0.3s;font-family:'Poppins',sans-serif;display:inline-flex;align-items:center;gap:10px}
.btn-edit{background:linear-gradient(135deg,var(--wood-medium),var(--wood-dark));color:white}
.btn-edit:hover{transform:translateY(-3px);box-shadow:0 8px 20px rgba(141,110,99,0.3)}
.btn-delete{background:#C62828;color:white}
.btn-delete:hover{background:#B71C1C;transform:translateY(-3px);box-shadow:0 8px 20px rgba(198,40,40,0.3)}
.modal{display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.7);z-index:9999;justify-content:center;align-items:center;padding:20px}
.modal.active{display:flex}
.modal-content{background:white;border-radius:20px;padding:40px;max-width:500px;width:100%;position:relative;animation:slideDown 0.3s}
@keyframes slideDown{from{opacity:0;transform:translateY(-50px)}to{opacity:1;transform:translateY(0)}}
.modal-close{position:absolute;top:15px;right:15px;background:none;border:none;font-size:32px;cursor:pointer;color:#999;width:40px;height:40px;display:flex;align-items:center;justify-content:center;border-radius:50%;transition:all 0.3s}
.modal-close:hover{background:#f0f0f0;color:var(--wood-dark);transform:rotate(90deg)}
.modal-title{font-family:'Crimson Pro',serif;font-size:1.8rem;color:var(--wood-dark);margin-bottom:25px;text-align:center}
.form-group{margin-bottom:20px;text-align:left}
.form-group label{display:block;margin-bottom:8px;font-weight:600;color:var(--text-dark)}
.form-group input{width:100%;padding:14px 18px;border:2px solid var(--cream);border-radius:10px;font-size:1rem;transition:all 0.3s;font-family:'Poppins',sans-serif}
.form-group input:focus{outline:none;border-color:var(--wood-medium);box-shadow:0 0 0 4px rgba(141,110,99,0.1)}
.btn-primary{background:linear-gradient(135deg,var(--wood-medium),var(--wood-dark));color:white;width:100%;padding:16px}
.btn-primary:hover{transform:translateY(-2px)}
.alert{padding:15px 20px;border-radius:10px;margin-bottom:30px;text-align:left}
.alert-success{background:#E8F5E9;color:#2E7D32;border-left:4px solid #2E7D32}
.alert-danger{background:#FFEBEE;color:#C62828;border-left:4px solid #C62828}
</style>

@if(session('success'))
<div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
@endif

<div class="account-container">
    <div class="profile-photo">
        <div class="profile-initials">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
    </div>
    
    <div class="profile-info">
        <div class="info-item">
            <span class="info-label">Nama</span>
            <span class="info-value">{{ $user->name }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Email</span>
            <span class="info-value">{{ $user->email }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Password</span>
            <span class="password-value">••••••••</span>
        </div>
    </div>
    
    <div class="action-buttons">
        <button onclick="openEditModal()" class="btn btn-edit">
            <i class="fas fa-edit"></i> Edit Akun
        </button>
        <button onclick="confirmDelete()" class="btn btn-delete">
            <i class="fas fa-trash-alt"></i> Hapus Akun
        </button>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal" id="editModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeEditModal()">&times;</button>
        <h2 class="modal-title">Edit Akun</h2>
        
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')<p style="color:#C62828;font-size:0.85rem;margin-top:5px">{{ $message }}</p>@enderror
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')<p style="color:#C62828;font-size:0.85rem;margin-top:5px">{{ $message }}</p>@enderror
            </div>
            
            <div class="form-group">
                <label>Password Baru (kosongkan jika tidak ingin mengubah)</label>
                <input type="password" name="password" placeholder="Minimal 8 karakter">
                @error('password')<p style="color:#C62828;font-size:0.85rem;margin-top:5px">{{ $message }}</p>@enderror
            </div>
            
            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" placeholder="Ulangi password baru">
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </form>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="deleteForm" action="{{ route('profile.delete') }}" method="POST" style="display:none">
    @csrf
    @method('DELETE')
</form>

<script>
function openEditModal() {
    document.getElementById('editModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

function confirmDelete() {
    Swal.fire({
        title: 'Hapus Akun?',
        html: '<p style="color:#666;margin-top:10px">Tindakan ini akan menghapus akun Anda secara <strong>permanen</strong> beserta semua data.</p><p style="color:#C62828;font-weight:600;margin-top:15px">Tindakan ini tidak dapat dibatalkan!</p>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#C62828',
        cancelButtonColor: '#E0E0E0',
        confirmButtonText: 'Ya, Hapus Akun',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm').submit();
        }
    });
}

// Close modal when clicking outside
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});

// Close modal with Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeEditModal();
});

// Auto open edit modal if errors exist
@if($errors->any())
    openEditModal();
@endif
</script>
@endsection