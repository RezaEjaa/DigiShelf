<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Digishelf</title>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:wght@600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --wood-dark: #5D4037;
            --wood-medium: #8D6E63;
            --cream: #F5F1E8;
            --cream-dark: #E8DCC8;
            --accent: #D4A574;
            --text-dark: #3E2723;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, rgba(93,64,55,0.95), rgba(78,52,46,0.95)), url('/img/ui/background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
        }
        
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255,255,255,0.1);
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(10px);
        }
        .back-button:hover {
            background: rgba(255,255,255,0.2);
            color: white;
            transform: translateX(-5px);
        }
        
        .auth-container {
            max-width: 900px;
            width: 100%;
            margin: 0 auto;
        }
        .auth-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
        .auth-left {
            background: linear-gradient(135deg, #6D4C41, #4E342E);
            padding: 60px 40px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }
        .auth-icon {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.1);
            border: 2px solid var(--accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
        }
        .auth-icon i { font-size: 36px; color: var(--accent); }
        .auth-left h2 {
            font-family: 'Crimson Pro', serif;
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .auth-left p {
            font-size: 0.95rem;
            opacity: 0.9;
            margin-bottom: 30px;
        }
        .auth-features {
            list-style: none;
            text-align: left;
            display: inline-block;
        }
        .auth-features li {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .auth-features li::before {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: var(--accent);
        }
        
        .auth-right {
            padding: 60px 50px;
            background: var(--cream);
        }
        .auth-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .auth-logo img {
            width: 120px;
        }
        .auth-title {
            font-family: 'Crimson Pro', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-dark);
            text-align: center;
            margin-bottom: 30px;
        }
        .form-label {
            font-weight: 600;
            color: var(--wood-dark);
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--wood-medium);
            z-index: 3;
        }
        .form-control {
            width: 100%;
            padding: 14px 18px 14px 45px;
            border: 2px solid var(--cream-dark);
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--wood-medium);
            box-shadow: 0 0 0 4px rgba(141,110,99,0.1);
        }
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--wood-medium);
            cursor: pointer;
            z-index: 3;
        }
        .btn-wood {
            width: 100%;
            background: linear-gradient(135deg, var(--wood-medium), var(--wood-dark));
            color: white;
            padding: 14px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(93,64,55,0.3);
            cursor: pointer;
        }
        .btn-wood:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(93,64,55,0.5);
        }
        .auth-link {
            text-align: center;
            margin-top: 25px;
            color: var(--wood-dark);
        }
        .auth-link a {
            color: var(--wood-medium);
            font-weight: 600;
            text-decoration: none;
        }
        .auth-link a:hover {
            text-decoration: underline;
        }
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .alert-danger {
            background: #FFEBEE;
            color: #C62828;
            border-left: 4px solid #C62828;
        }
        
        @media (max-width: 768px) {
            .auth-card {
                grid-template-columns: 1fr;
            }
            .auth-left {
                display: none;
            }
            .auth-right {
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>

    <a href="/" class="back-button">
        <i class="fas fa-arrow-left"></i>
        Kembali ke Beranda
    </a>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-left">
                <div class="auth-icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h2>Selamat Datang Kembali!</h2>
                <p>Masuk untuk mengakses perpustakaan digital Anda</p>
                <ul class="auth-features">
                    <li>Akses katalog buku digital</li>
                    <li>Pantau peminjaman real-time</li>
                    <li>Kelola koleksi dengan mudah</li>
                    <li>Laporan lengkap</li>
                </ul>
            </div>

            <div class="auth-right">
                <div class="auth-logo">
                    <img src="{{ asset('img/ui/logo.png') }}" alt="Digishelf">
                </div>

                <h3 class="auth-title">Login ke Akun</h3>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="/login" autocomplete="off">
                    @csrf

                    <div>
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="nama@email.com" autocomplete="off" required autofocus>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" name="password" class="form-control" id="password" placeholder="••••••••" autocomplete="new-password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-wood">
                        <i class="fas fa-sign-in-alt"></i> Masuk
                    </button>
                </form>

                <div class="auth-link">
                    Belum punya akun? <a href="/register">Daftar Sekarang</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>