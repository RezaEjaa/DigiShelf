<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Digishelf')</title>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:wght@600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --wood-dark: #5D4037;
            --wood-medium: #8D6E63;
            --wood-light: #A1887F;
            --cream: #F5F1E8;
            --text-dark: #3E2723;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--cream);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Navbar */
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
        }
        .navbar-logo {
            width: 45px;
            height: 45px;
        }
        .navbar-title {
            font-family: 'Crimson Pro', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--wood-dark);
        }
        .navbar-menu {
            display: flex;
            gap: 5px;
            list-style: none;
            align-items: center;
        }
        .navbar-menu a {
            padding: 10px 20px;
            color: var(--text-dark);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .navbar-menu a:hover,
        .navbar-menu a.active {
            background: var(--cream);
            color: var(--wood-dark);
        }
        .navbar-menu a.active {
            font-weight: 600;
        }
        .navbar-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .user-info {
            text-align: right;
        }
        .user-name {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
        }
        .user-role {
            font-size: 0.8rem;
            color: #666;
        }
        .user-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--wood-medium), var(--wood-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        .btn-logout {
            background: transparent;
            border: 2px solid var(--wood-medium);
            color: var(--wood-medium);
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-logout:hover {
            background: var(--wood-medium);
            color: white;
        }
        
        /* Mobile Menu Toggle */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--wood-dark);
            cursor: pointer;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
            padding: 30px;
        }
        
        /* Footer */
        .footer {
            background: #3E2723;
            color: rgba(255,255,255,0.8);
            padding: 40px 30px 20px;
            margin-top: auto;
        }
        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        .footer-content {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 30px;
        }
        .footer-brand h3 {
            font-family: 'Crimson Pro', serif;
            color: white;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }
        .footer-brand p {
            line-height: 1.6;
            font-size: 0.9rem;
        }
        .footer-section h4 {
            color: white;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .footer-links {
            list-style: none;
        }
        .footer-links li {
            margin-bottom: 10px;
        }
        .footer-links a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: color 0.3s;
            font-size: 0.9rem;
        }
        .footer-links a:hover {
            color: var(--cream);
        }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 20px;
            text-align: center;
            color: rgba(255,255,255,0.6);
            font-size: 0.85rem;
        }
        
        /* Mobile Responsive */
        @media (max-width: 992px) {
            .navbar-container {
                padding: 0 20px;
            }
            .navbar-menu {
                position: fixed;
                top: 70px;
                left: 0;
                right: 0;
                background: white;
                flex-direction: column;
                padding: 20px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                display: none;
                gap: 0;
            }
            .navbar-menu.show {
                display: flex;
            }
            .navbar-menu a {
                width: 100%;
                padding: 15px 20px;
                border-radius: 0;
                border-bottom: 1px solid var(--cream);
            }
            .mobile-toggle {
                display: block;
            }
            .navbar-user {
                display: none;
            }
            .main-content {
                padding: 20px;
            }
            .footer-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ route('user.dashboard') }}" class="navbar-brand">
                <img src="{{ asset('img/ui/logo.png') }}" alt="Digishelf" class="navbar-logo">
                <span class="navbar-title">Digishelf</span>
            </a>

            <button class="mobile-toggle" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </button>

            <ul class="navbar-menu" id="navbarMenu">
                @yield('navbar-menu')
                <li class="mobile-only">
                    <form action="{{ route('logout') }}" method="POST" style="width:100%">
                        @csrf
                        <button type="submit" style="width:100%;text-align:left;background:none;border:none;padding:15px 20px;cursor:pointer;color:var(--text-dark);font-family:'Poppins',sans-serif;font-weight:500;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>

            <div class="navbar-user">
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">{{ Auth::user()->role }}</div>
                </div>
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="display:inline">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-brand">
                    <h3>Digishelf</h3>
                    <p>Platform perpustakaan digital yang mudah, transparan, dan terpercaya untuk mengelola koleksi buku Anda.</p>
                </div>

                <div class="footer-section">
                    <h4>Navigasi</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('user.books') }}">Koleksi Buku</a></li>
                        <li><a href="{{ route('user.borrowings') }}">Peminjaman</a></li>
                        <li><a href="{{ route('user.history') }}">Riwayat</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>Akun</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('user.account') }}">Profil Saya</a></li>
                        <li><a href="{{ route('user.favorites') }}">Favorit</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>Bantuan</h4>
                    <ul class="footer-links">
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Hubungi Kami</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Digishelf. Platform Perpustakaan Digital Terpercaya.</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMenu() {
            document.getElementById('navbarMenu').classList.toggle('show');
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('navbarMenu');
            const toggle = document.querySelector('.mobile-toggle');
            
            if (window.innerWidth <= 992) {
                if (!menu.contains(e.target) && !toggle.contains(e.target)) {
                    menu.classList.remove('show');
                }
            }
        });
    </script>

    @stack('scripts')
</body>
</html>