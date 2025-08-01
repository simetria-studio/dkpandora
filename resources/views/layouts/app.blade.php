<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DK Pandora - Grand Fantasia Violet')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        /* Version: 1.0.1 - Force cache refresh */
        :root {
            --primary-color: #6a0dad;
            --secondary-color: #ff6b35;
            --accent-color: #00d4ff;
            --dark-bg: #0a0a0a;
            --card-bg: #1a1a1a;
            --text-light: #ffffff;
            --text-muted: #b0b0b0;
            --gradient-primary: linear-gradient(135deg, #6a0dad 0%, #ff6b35 100%);
            --gradient-secondary: linear-gradient(135deg, #00d4ff 0%, #6a0dad 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: var(--dark-bg);
            color: var(--text-light);
            line-height: 1.6;
        }

        .navbar-brand {
            font-family: 'Orbitron', monospace;
            font-weight: 900;
            font-size: 1.8rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar {
            background: rgba(26, 26, 26, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(106, 13, 173, 0.3);
        }

        .navbar-nav .nav-link {
            color: var(--text-light) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .navbar-nav .nav-link:hover {
            color: var(--accent-color) !important;
            transform: translateY(-2px);
        }

        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--gradient-primary);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .navbar-nav .nav-link:hover::after {
            width: 100%;
        }

        .hero-section {
            background: var(--gradient-primary);
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .card {
            background: var(--card-bg);
            border: 1px solid rgba(106, 13, 173, 0.2);
            border-radius: 15px;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(106, 13, 173, 0.3);
            border-color: var(--primary-color);
        }

        .card-title {
            color: #ffffff !important;
        }

        .card-text {
            color: #e0e0e0 !important;
        }

        .card-body {
            color: #ffffff !important;
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .card:hover .card-img-top {
            transform: scale(1.05);
        }

        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(106, 13, 173, 0.4);
        }

        .btn-secondary {
            background: var(--gradient-secondary);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 212, 255, 0.4);
        }

        .badge {
            border-radius: 20px;
            padding: 8px 16px;
            font-weight: 600;
        }

        .badge-common { background: #6c757d; }
        .badge-rare { background: #007bff; }
        .badge-epic { background: #6f42c1; }
        .badge-legendary {
            background: linear-gradient(45deg, #ffd700, #ff6b35);
            color: #000;
        }

        .price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent-color);
        }

        .footer {
            background: var(--card-bg);
            border-top: 1px solid rgba(106, 13, 173, 0.3);
            padding: 50px 0 20px;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--secondary-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-control {
            background: var(--card-bg);
            border: 1px solid rgba(106, 13, 173, 0.3);
            color: #ffffff !important;
            border-radius: 10px;
        }

        .form-control:focus {
            background: var(--card-bg);
            border-color: var(--primary-color);
            color: #ffffff !important;
            box-shadow: 0 0 0 0.2rem rgba(106, 13, 173, 0.25);
        }

        .form-control::placeholder {
            color: #a0a0a0 !important;
        }

        .form-label {
            color: #ffffff !important;
            font-weight: 500;
        }

        .form-text {
            color: #b0b0b0 !important;
        }

        .form-select {
            background: var(--card-bg);
            border: 1px solid rgba(106, 13, 173, 0.3);
            color: #ffffff !important;
            border-radius: 10px;
        }

        .form-select:focus {
            background: var(--card-bg);
            border-color: var(--primary-color);
            color: #ffffff !important;
            box-shadow: 0 0 0 0.2rem rgba(106, 13, 173, 0.25);
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .alert-info {
            background: rgba(13, 202, 240, 0.1);
            color: #e0f7ff !important;
            border: 1px solid rgba(13, 202, 240, 0.3);
        }

        .alert-success {
            background: rgba(25, 135, 84, 0.1);
            color: #d1f2e1 !important;
            border: 1px solid rgba(25, 135, 84, 0.3);
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #f8d7da !important;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .text-muted {
            color: #b0b0b0 !important;
        }

        .small {
            color: #c0c0c0 !important;
        }

        .list-unstyled li a {
            color: #d0d0d0 !important;
        }

        .list-unstyled li a:hover {
            color: #ffffff !important;
        }

        .pagination .page-link {
            background: var(--card-bg);
            border: 1px solid rgba(106, 13, 173, 0.3);
            color: var(--text-light);
        }

        .pagination .page-link:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .pagination .page-item.active .page-link {
            background: var(--gradient-primary);
            border-color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 0;
            }

            .navbar-brand {
                font-size: 1.4rem;
            }
        }

        /* Gold Purchase Section Styles */
        .gold-purchase-section {
            background: linear-gradient(135deg, rgba(106, 13, 173, 0.1) 0%, rgba(25, 25, 35, 0.1) 100%);
        }

        .gold-purchase-section .card {
            border: 1px solid rgba(106, 13, 173, 0.3);
            background: var(--card-bg);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .gold-purchase-section .card-title {
            color: #ffffff !important;
        }

        .gold-purchase-section .card-text {
            color: #e0e0e0 !important;
        }

        .gold-purchase-section .form-label {
            color: #ffffff !important;
            font-weight: 600;
        }

        .gold-purchase-section .form-text {
            color: #b0b0b0 !important;
        }

        .gold-purchase-section .price-display .card {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border: 1px solid rgba(106, 13, 173, 0.5);
        }

        .gold-purchase-section .price-display h4 {
            color: #ffffff !important;
        }

        .gold-purchase-section .price-display small {
            color: #d0d0d0 !important;
        }

        .gold-purchase-section .btn-outline-secondary {
            border-color: rgba(106, 13, 173, 0.5);
            color: #ffffff !important;
            transition: all 0.3s ease;
        }

        .gold-purchase-section .btn-outline-secondary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white !important;
            transform: translateY(-2px);
        }

        .gold-purchase-section .input-group-text {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            font-weight: bold;
        }

        .gold-purchase-section .form-control {
            background: var(--card-bg);
            border-color: rgba(106, 13, 173, 0.3);
            color: #ffffff !important;
        }

        .gold-purchase-section .form-control:focus {
            background: var(--card-bg);
            border-color: var(--primary-color);
            color: #ffffff !important;
            box-shadow: 0 0 0 0.2rem rgba(106, 13, 173, 0.25);
        }

        .gold-purchase-section .form-control::placeholder {
            color: #a0a0a0 !important;
        }

        /* Featured Products Section Styles */
        .featured-products {
            background: linear-gradient(135deg, rgba(15, 52, 96, 0.1) 0%, rgba(26, 26, 46, 0.1) 100%);
        }

        .featured-products .product-card {
            background: var(--card-bg);
            border: 1px solid rgba(106, 13, 173, 0.3);
            transition: all 0.3s ease;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .featured-products .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            border-color: var(--primary-color);
        }

        .featured-products .card-img-top {
            transition: transform 0.3s ease;
        }

        .featured-products .product-card:hover .card-img-top {
            transform: scale(1.05);
        }

        .featured-products .card-title {
            color: #ffffff !important;
            font-weight: 600;
        }

        .featured-products .card-text {
            color: #e0e0e0 !important;
        }

        .featured-products .badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
            font-weight: 600;
        }

        .featured-products .price-display {
            background: rgba(40, 167, 69, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .featured-products .price-display .h4 {
            color: #28a745 !important;
            margin: 0;
        }

        .featured-products .stock-info .badge {
            font-size: 0.7rem;
            padding: 0.4rem 0.6rem;
        }

        .featured-products .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .featured-products .btn-primary:hover {
            background: #7c3aed;
            border-color: #7c3aed;
            transform: translateY(-2px);
        }

        .featured-products .btn-outline-primary {
            border-color: var(--primary-color);
            color: var(--primary-color);
            font-weight: 600;
        }

        .featured-products .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        /* Pagination Styles */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
        }

        .pagination {
            margin: 0;
            gap: 0.25rem;
        }

        .pagination .page-link {
            background: var(--card-bg);
            border: 1px solid rgba(106, 13, 173, 0.3);
            color: #ffffff;
            padding: 0.5rem 0.75rem;
            transition: all 0.3s ease;
            border-radius: 0.375rem;
            min-width: 40px;
            text-align: center;
        }

        .pagination .page-link:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .pagination .page-item.active .page-link {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            font-weight: 600;
        }

        .pagination .page-item.disabled .page-link {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.5);
            cursor: not-allowed;
        }

        .pagination .page-link:focus {
            box-shadow: 0 0 0 0.2rem rgba(106, 13, 173, 0.25);
            border-color: var(--primary-color);
        }

        .pagination .page-item:not(:last-child) {
            margin-right: 0.25rem;
        }

        /* Dropdown Styles */
        .dropdown-menu {
            background: var(--card-bg);
            border: 1px solid rgba(106, 13, 173, 0.3);
            border-radius: 10px;
        }

        .dropdown-item {
            color: #ffffff !important;
        }

        .dropdown-item:hover {
            background: rgba(106, 13, 173, 0.2);
            color: #ffffff !important;
        }

        .dropdown-divider {
            border-color: rgba(106, 13, 173, 0.3);
        }
        .name-product {
            color: #1D1B1BFF !important;
        }

        /* Force override for product names in cart */
        .table .name-product {
            color: #1D1B1BFF !important;
        }

        .table h4.name-product {
            color: #1D1B1BFF !important;
        }

        /* Additional Text Contrast Improvements */
        h1, h2, h3, h4, h5, h6 {
            color: #ffffff;
        }



        .lead {
            color: #e0e0e0 !important;
        }

        .display-4, .display-6 {
            color: #ffffff !important;
        }

        /* Cart Table Styles */
        .table {
            color: #ffffff !important;
            margin-bottom: 0;
            background: var(--card-bg);
        }

        .table thead th {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #ffffff !important;
            font-weight: 600;
            border: none;
            padding: 1rem 0.75rem;
        }

        .table tbody tr {
            transition: all 0.3s ease;
            background: var(--card-bg);
        }

        .table tbody tr:hover {
            background: rgba(106, 13, 173, 0.15);
        }

        .table tbody td {
            border: none;
            padding: 1rem 0.75rem;
            vertical-align: middle;
            color: #ffffff !important;
        }

        .table .border-bottom {
            border-bottom: 1px solid rgba(106, 13, 173, 0.3) !important;
        }

        .badge-sm {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        /* Override Bootstrap table styles */
        .table-hover > tbody > tr:hover {
            background: rgba(106, 13, 173, 0.15) !important;
            color: #ffffff !important;
        }

        .table-hover > tbody > tr:hover > td {
            color: #ffffff !important;
        }

        /* Ensure all text in table is white */
        /* .table h6, .table p, .table span {
            color: #ffffff ;
        } */

        .table .text-muted {
            color: #b0b0b0 !important;
        }

        /* Cart header specific styles */
        .card-header.bg-gradient-primary {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
            color: #ffffff !important;
        }

        .card-header.bg-gradient-primary .badge {
            background: rgba(255, 255, 255, 0.9) !important;
            color: #1a1a2e !important;
        }
    </style>

    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('products.index') }}">
                <i class="fas fa-dragon me-2"></i>DK Pandora
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">
                            <i class="fas fa-store me-1"></i>Loja
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index', ['type' => 'item']) }}">
                            <i class="fas fa-sword me-1"></i>Itens
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index', ['type' => 'gold']) }}">
                            <i class="fas fa-coins me-1"></i>Gold
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item position-relative">
                        <a class="nav-link" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart"></i>
                            @php
                                $cartCount = count(session()->get('cart', []));
                            @endphp
                            @if($cartCount > 0)
                                <span class="cart-badge">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>

                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                @if(Auth::user()->is_admin)
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-crown me-2"></i>Painel Admin
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('orders.index') }}">
                                    <i class="fas fa-list me-2"></i>Meus Pedidos
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Sair
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Entrar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>Cadastrar
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main style="margin-top: 80px;">
        @if(session('success'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="mb-3">
                        <i class="fas fa-dragon me-2"></i>DK Pandora
                    </h5>
                    <p class="text-muted">
                        Sua loja confiável para itens e gold do Grand Fantasia Violet.
                        Entrega rápida e segura para todos os servidores.
                    </p>
                </div>
                <div class="col-md-4">
                    <h6 class="mb-3">Links Rápidos</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('products.index') }}" class="text-muted text-decoration-none">Loja</a></li>
                        <li><a href="{{ route('products.index', ['type' => 'item']) }}" class="text-muted text-decoration-none">Itens</a></li>
                        <li><a href="{{ route('products.index', ['type' => 'gold']) }}" class="text-muted text-decoration-none">Gold</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="mb-3">Suporte</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Central de Ajuda</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Política de Reembolso</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Termos de Uso</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="text-muted mb-0">
                    &copy; 2024 DK Pandora. Todos os direitos reservados.
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')
</body>
</html>
