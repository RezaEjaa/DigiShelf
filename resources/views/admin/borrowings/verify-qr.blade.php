@extends('layouts.app')

@section('title', 'Verifikasi QR - Digishelf')
@section('page-title', 'Verifikasi QR Code')
@section('page-subtitle', 'Scan atau input QR Code peminjaman')

@section('sidebar-menu')
    <li><a href="{{ route('admin.dashboard') }}"><i class="fas fa-th-large"></i><span>Dashboard</span></a></li>
    <li><a href="{{ route('admin.books.index') }}"><i class="fas fa-book"></i><span>Kelola Buku</span></a></li>
    <li><a href="{{ route('admin.books.create') }}"><i class="fas fa-plus-circle"></i><span>Tambah Buku</span></a></li>
    <li><a href="{{ route('admin.borrowings.index') }}"><i class="fas fa-exchange-alt"></i><span>Kelola Peminjaman</span></a></li>
    <li><a href="{{ route('admin.verify-qr.index') }}" class="active"><i class="fas fa-qrcode"></i><span>Verifikasi QR</span></a></li>
    <li><a href="{{ route('admin.borrowings.history') }}"><i class="fas fa-history"></i><span>Riwayat Peminjaman</span></a></li>
    <li><a href="{{ route('admin.users.index') }}"><i class="fas fa-users"></i><span>Kelola Pengguna</span></a></li>
    <li class="logout-section">
        <form action="{{ url('/logout') }}" method="POST" class="logout-form">@csrf
            <button type="submit"><i class="fas fa-sign-out-alt"></i><span>Logout</span></button>
        </form>
    </li>
@endsection

@section('content')
<style>
    :root { --wood-dark:#5D4037; --wood-medium:#8D6E63; --wood-light:#D7CCC8; --cream:#FFF8E1; --text-dark:#3E2723; }

    .top-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 28px;
    }

    .card {
        background: white; border-radius: 16px; padding: 26px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.08);
    }
    .card-title {
        font-family: 'Crimson Pro', serif;
        font-size: 1.2rem; font-weight: 700; color: var(--text-dark);
        margin-bottom: 18px; padding-bottom: 11px;
        border-bottom: 2px solid var(--cream);
        display: flex; align-items: center; gap: 9px;
    }
    .card-title i { color: var(--wood-medium); }

    /* ── Input QR ── */
    .qr-input-wrapper { display: flex; gap: 10px; }
    .qr-input {
        flex: 1; padding: 13px 16px;
        border: 2px solid #E0E0E0; border-radius: 10px;
        font-size: 0.95rem; font-family: 'Courier New', monospace;
        font-weight: 600; letter-spacing: 1px;
        text-transform: uppercase;
        transition: border-color 0.2s;
    }
    .qr-input:focus { outline: none; border-color: var(--wood-medium); }
    .btn-scan {
        padding: 13px 20px;
        background: linear-gradient(135deg, var(--wood-medium), var(--wood-dark));
        color: white; border: none; border-radius: 10px;
        font-size: 0.9rem; font-weight: 600; cursor: pointer;
        display: flex; align-items: center; gap: 8px;
        transition: all 0.3s; white-space: nowrap;
    }
    .btn-scan:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(93,64,55,0.3); }

    /* Camera scan */
    .camera-btn {
        width: 100%; margin-top: 12px;
        padding: 11px; border: 2px dashed #BDBDBD;
        border-radius: 10px; background: #FAFAFA;
        color: #888; font-size: 0.88rem; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        transition: all 0.2s; font-family: 'Poppins', sans-serif;
    }
    .camera-btn:hover { border-color: var(--wood-medium); color: var(--wood-dark); background: #FFF8E1; }
    #cameraPreview { display: none; margin-top: 12px; border-radius: 10px; overflow: hidden; }
    #cameraPreview video { width: 100%; border-radius: 8px; }

    /* Alert boxes */
    .alert { border-radius: 10px; padding: 14px 18px; font-size: 0.88rem; margin-bottom: 20px; }
    .alert-success { background:#E8F5E9; color:#2E7D32; }
    .alert-error   { background:#FFEBEE; color:#C62828; }
    .alert-info    { background:#E3F2FD; color:#1565C0; }

    /* ── Result card ── */
    .result-card {
        background: white; border-radius: 16px; padding: 26px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.08);
        margin-bottom: 28px; border-left: 5px solid var(--wood-medium);
    }
    .result-header {
        display: flex; justify-content: space-between; align-items: flex-start;
        margin-bottom: 18px; flex-wrap: wrap; gap: 12px;
    }
    .result-qr {
        font-family: 'Courier New', monospace;
        font-size: 1.2rem; font-weight: 700; color: var(--wood-dark);
    }
    .badge {
        display: inline-block; padding: 5px 14px;
        border-radius: 20px; font-size: 0.78rem; font-weight: 600;
    }
    .badge-pending   { background:#FFF3E0; color:#E65100; }
    .badge-active    { background:#E8F5E9; color:#2E7D32; }
    .badge-cancelled { background:#FFEBEE; color:#C62828; }
    .badge-returned  { background:#EDE7F6; color:#4527A0; }

    .result-info-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 10px;
        margin-bottom: 18px;
    }
    .result-info-item { font-size: 0.85rem; }
    .result-info-item .lbl { color: #888; margin-bottom: 2px; }
    .result-info-item .val { font-weight: 600; color: var(--text-dark); }

    /* Buku list */
    .book-row {
        display: flex; align-items: center; gap: 12px;
        padding: 10px 0; border-bottom: 1px solid #F5F5F5;
    }
    .book-row:last-child { border-bottom: none; }
    .b-thumb {
        width: 42px; height: 63px; border-radius: 5px;
        overflow: hidden; flex-shrink: 0;
        box-shadow: 1px 1px 5px rgba(0,0,0,0.15);
        display: flex; align-items: center; justify-content: center;
    }
    .b-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .b-thumb i { font-size: 18px; color: rgba(255,255,255,0.6); }
    .b-info { flex: 1; min-width: 0; }
    .b-title { font-size: 0.86rem; font-weight: 600; color: var(--text-dark); }
    .b-author { font-size: 0.75rem; color: #888; }

    /* Action buttons */
    .action-row { display: flex; gap: 12px; margin-top: 20px; flex-wrap: wrap; }
    .btn-confirm {
        padding: 12px 26px;
        background: linear-gradient(135deg, #66BB6A, #388E3C);
        color: white; border: none; border-radius: 10px;
        font-size: 0.9rem; font-weight: 600; cursor: pointer;
        display: flex; align-items: center; gap: 8px;
        transition: all 0.3s;
    }
    .btn-confirm:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(56,142,60,0.3); }
    .btn-return {
        padding: 12px 26px;
        background: linear-gradient(135deg, #7986CB, #3949AB);
        color: white; border: none; border-radius: 10px;
        font-size: 0.9rem; font-weight: 600; cursor: pointer;
        display: flex; align-items: center; gap: 8px;
        transition: all 0.3s;
    }
    .btn-return:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(57,73,171,0.3); }

    /* ── Pending list ── */
    .section-title {
        font-family: 'Crimson Pro', serif;
        font-size: 1.3rem; font-weight: 700;
        color: var(--text-dark); margin-bottom: 16px;
    }
    .pending-table { width: 100%; border-collapse: collapse; }
    .pending-table th {
        background: var(--cream); padding: 12px 14px;
        text-align: left; font-size: 0.85rem; font-weight: 600;
        color: var(--text-dark); border-bottom: 2px solid #E0E0E0;
        white-space: nowrap;
    }
    .pending-table td {
        padding: 12px 14px; border-bottom: 1px solid #F5F5F5;
        font-size: 0.84rem; vertical-align: middle;
    }
    .pending-table tr:hover { background: #FAFAFA; }
    .table-responsive { overflow-x: auto; }

    .countdown-sm { font-size: 0.75rem; color: #E65100; font-weight: 600; }

    /* Responsive */
    @media (max-width: 1024px) { .top-grid { grid-template-columns: 1fr; } }
    @media (max-width: 768px) {
        .result-info-grid { grid-template-columns: 1fr; }
        .action-row { flex-direction: column; }
        .btn-confirm, .btn-return { width: 100%; justify-content: center; }
    }
</style>

{{-- Flash messages --}}
@if(session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error"><i class="fas fa-times-circle"></i> {{ session('error') }}</div>
@endif
@if(session('qr_error'))
    <div class="alert alert-error"><i class="fas fa-qrcode"></i> {{ session('qr_error') }}</div>
@endif
@if(session('qr_info'))
    <div class="alert alert-info"><i class="fas fa-info-circle"></i> {{ session('qr_info') }}</div>
@endif

{{-- ── QR Input + Kamera ── --}}
<div class="top-grid">
    <div class="card">
        <div class="card-title"><i class="fas fa-keyboard"></i> Input Kode QR Manual</div>
        <form action="{{ route('admin.verify-qr.verify') }}" method="POST">
            @csrf
            <div class="qr-input-wrapper">
                <input type="text" name="qr_code" class="qr-input"
                       placeholder="DIGI-XXXXXXXX"
                       value="{{ old('qr_code') }}"
                       autocomplete="off" autofocus>
                <button type="submit" class="btn-scan">
                    <i class="fas fa-search"></i> Cek
                </button>
            </div>
        </form>

        <button class="camera-btn" onclick="toggleCamera()">
            <i class="fas fa-camera"></i> Scan dengan Kamera
        </button>

        <div id="cameraPreview">
            <video id="cameraVideo" autoplay playsinline></video>
            <p style="text-align:center;font-size:0.78rem;color:#888;margin-top:6px;">
                Arahkan kamera ke QR Code
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-title"><i class="fas fa-lightbulb"></i> Panduan Verifikasi</div>
        <div style="font-size:0.85rem;color:#555;line-height:1.8;">
            <p style="margin-bottom:10px;"><strong>Langkah verifikasi peminjaman:</strong></p>
            <ol style="padding-left:18px;">
                <li>User menunjukkan QR Code dari halaman detail peminjaman</li>
                <li>Scan atau ketik kode di kolom sebelah kiri</li>
                <li>Periksa data peminjaman yang muncul</li>
                <li>Klik <strong style="color:#388E3C">Konfirmasi & Serahkan Buku</strong></li>
                <li>Stok buku otomatis berkurang</li>
            </ol>
            <p style="margin-top:12px;"><strong>Pengembalian buku:</strong></p>
            <ol style="padding-left:18px;">
                <li>Scan QR Code yang sama saat buku dikembalikan</li>
                <li>Klik <strong style="color:#3949AB">Konfirmasi Pengembalian</strong></li>
                <li>Stok buku otomatis kembali bertambah</li>
            </ol>
        </div>
    </div>
</div>

{{-- ── Hasil Scan QR ── --}}
@if(session('qr_result'))
    @php $r = session('qr_result'); $colors = ['linear-gradient(135deg,#A1887F,#8D6E63)','linear-gradient(135deg,#7986CB,#5C6BC0)','linear-gradient(135deg,#81C784,#66BB6A)','linear-gradient(135deg,#FFB74D,#FFA726)','linear-gradient(135deg,#E57373,#EF5350)']; @endphp
    <div class="result-card">
        <div class="result-header">
            <div>
                <div class="result-qr"><i class="fas fa-qrcode"></i> {{ $r->qr_code }}</div>
                <div style="font-size:0.82rem;color:#888;margin-top:4px;">
                    Ditemukan — {{ $r->items->count() }} buku
                </div>
            </div>
            <span class="badge badge-{{ $r->status }}">{{ $r->statusLabel() }}</span>
        </div>

        <div class="result-info-grid">
            <div class="result-info-item">
                <div class="lbl">Peminjam</div>
                <div class="val">{{ $r->user->name }}</div>
            </div>
            <div class="result-info-item">
                <div class="lbl">Email</div>
                <div class="val">{{ $r->user->email }}</div>
            </div>
            <div class="result-info-item">
                <div class="lbl">Tanggal Pengambilan</div>
                <div class="val">{{ $r->pickup_date->format('d M Y') }}</div>
            </div>
            <div class="result-info-item">
                <div class="lbl">Tanggal Pengembalian</div>
                <div class="val">{{ $r->return_date->format('d M Y') }}</div>
            </div>
            @if($r->expires_at && $r->isPending())
            <div class="result-info-item">
                <div class="lbl">Batas Verifikasi</div>
                <div class="val" style="color:#E65100;">{{ $r->expires_at->format('d M Y H:i') }}</div>
            </div>
            @endif
        </div>

        {{-- Daftar buku --}}
        <div style="margin-bottom:6px;font-weight:600;font-size:0.85rem;color:var(--text-dark);">Buku yang dipinjam:</div>
        @foreach($r->items as $ci => $item)
            <div class="book-row">
                <div class="b-thumb" style="background:{{ $colors[$ci % 5] }};">
                    @if($item->book->cover_image)
                        <img src="{{ asset('img/covers/'.$item->book->cover_image) }}" alt="">
                    @else
                        <i class="fas fa-book"></i>
                    @endif
                </div>
                <div class="b-info">
                    <div class="b-title">{{ $item->book->title }}</div>
                    <div class="b-author">{{ $item->book->author }}</div>
                </div>
            </div>
        @endforeach

        {{-- Tombol aksi --}}
        @if(session('qr_action') === 'confirm' && $r->isPending())
            <div class="action-row">
                <form action="{{ route('admin.verify-qr.confirm') }}" method="POST">
                    @csrf
                    <input type="hidden" name="request_id" value="{{ $r->id }}">
                    <button type="submit" class="btn-confirm"
                            onclick="return confirm('Konfirmasi peminjaman dan serahkan buku ke user?')">
                        <i class="fas fa-check-circle"></i> Konfirmasi & Serahkan Buku
                    </button>
                </form>
            </div>
        @elseif($r->isActive())
            <div class="action-row">
                <form action="{{ route('admin.verify-qr.return') }}" method="POST">
                    @csrf
                    <input type="hidden" name="request_id" value="{{ $r->id }}">
                    <button type="submit" class="btn-return"
                            onclick="return confirm('Konfirmasi buku telah dikembalikan?')">
                        <i class="fas fa-undo"></i> Konfirmasi Pengembalian
                    </button>
                </form>
            </div>
        @endif
    </div>
@endif

{{-- ── Daftar Pending (menunggu diambil) ── --}}
@if($pendingRequests->isNotEmpty())
<div class="card">
    <div class="card-title"><i class="fas fa-hourglass-half"></i> Menunggu Diambil ({{ $pendingRequests->count() }})</div>
    <div class="table-responsive">
        <table class="pending-table">
            <thead>
                <tr>
                    <th>QR Code</th>
                    <th>Peminjam</th>
                    <th>Buku</th>
                    <th>Tgl Ambil</th>
                    <th>Sisa Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingRequests as $pr)
                    <tr>
                        <td style="font-family:'Courier New',monospace;font-weight:700;color:var(--wood-dark);">
                            {{ $pr->qr_code }}
                        </td>
                        <td>{{ $pr->user->name }}</td>
                        <td style="max-width:200px;">
                            {{ $pr->items->map(fn($i)=>$i->book->title)->join(', ') }}
                        </td>
                        <td>{{ $pr->pickup_date->format('d M Y') }}</td>
                        <td>
                            <span class="countdown-sm" data-expires="{{ $pr->expires_at->toIso8601String() }}">
                                Menghitung…
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.verify-qr.confirm') }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="request_id" value="{{ $pr->id }}">
                                <button type="submit" class="btn-confirm" style="padding:7px 14px;font-size:0.8rem;"
                                        onclick="return confirm('Konfirmasi peminjaman ini?')">
                                    <i class="fas fa-check"></i> Konfirmasi
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<script>
// Countdown semua baris pending
document.querySelectorAll('.countdown-sm[data-expires]').forEach(el => {
    const exp = new Date(el.dataset.expires);
    function tick() {
        const diff = exp - Date.now();
        if (diff <= 0) { el.textContent = 'KADALUARSA'; el.style.color='#C62828'; return; }
        const h = String(Math.floor(diff/3600000)).padStart(2,'0');
        const m = String(Math.floor((diff%3600000)/60000)).padStart(2,'0');
        const s = String(Math.floor((diff%60000)/1000)).padStart(2,'0');
        el.textContent = `${h}:${m}:${s}`;
        setTimeout(tick, 1000);
    }
    tick();
});

// Camera scan (menggunakan jsQR)
let cameraStream = null;
let cameraOn = false;

async function toggleCamera() {
    const preview = document.getElementById('cameraPreview');
    const video   = document.getElementById('cameraVideo');

    if (cameraOn) {
        if (cameraStream) cameraStream.getTracks().forEach(t => t.stop());
        preview.style.display = 'none';
        cameraOn = false;
        return;
    }

    try {
        cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
        video.srcObject = cameraStream;
        preview.style.display = 'block';
        cameraOn = true;

        // Load jsQR dynamically
        if (!window.jsQR) {
            const script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jsQR/1.4.0/jsQR.min.js';
            script.onload = startQrScan;
            document.head.appendChild(script);
        } else {
            startQrScan();
        }
    } catch(e) {
        alert('Kamera tidak dapat diakses. Gunakan input manual.');
    }
}

function startQrScan() {
    const video  = document.getElementById('cameraVideo');
    const canvas = document.createElement('canvas');
    const ctx    = canvas.getContext('2d');

    function scan() {
        if (!cameraOn) return;
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvas.width  = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height);
            if (code) {
                // Ambil bagian pertama (QR string: "DIGI-XXXX|...")
                const qrCode = code.data.split('|')[0];
                document.querySelector('.qr-input').value = qrCode;
                // Auto submit
                document.querySelector('.qr-input').closest('form').submit();
                return;
            }
        }
        requestAnimationFrame(scan);
    }
    video.addEventListener('loadedmetadata', () => requestAnimationFrame(scan));
}

// Auto-focus input
document.querySelector('.qr-input')?.focus();
</script>
@endsection