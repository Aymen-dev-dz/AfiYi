<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Espace Vendeur</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f4f7f6; 
            color: #2b3440;
        }
        /* Custom Modern SaaS overrides for Bootstrap */
        .navbar {
            background-color: #ffffff !important;
            border-bottom: 1px solid #eaeaea;
        }
        .navbar-brand { color: #4f46e5 !important; font-weight: 800; }
        .nav-link { color: #6b7280 !important; font-weight: 500; }
        .nav-link:hover { color: #111827 !important; }
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
        }
        .btn-primary {
            background-color: #4f46e5;
            border-color: #4f46e5;
            border-radius: 0.5rem;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #4338ca;
            border-color: #4338ca;
        }
        .nav-tabs {
            border-bottom: 2px solid #eaeaea;
        }
        .nav-tabs .nav-link {
            border: none;
            color: #6b7280 !important;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }
        .nav-tabs .nav-link.active {
            color: #4f46e5 !important;
            background-color: transparent;
            border-bottom: 2px solid #4f46e5;
        }
        .table > :not(caption) > * > * {
            padding: 1rem;
            border-bottom-color: #f3f4f6;
        }
        .badge {
            padding: 0.5em 0.75em;
            font-weight: 600;
            border-radius: 0.375rem;
        }
        .form-control, .form-select {
            border-radius: 0.5rem;
            padding: 0.6rem 1rem;
            border: 1px solid #d1d5db;
        }
        .form-control:focus, .form-select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.25);
        }
    </style>

    @livewireStyles
</head>
<body>

    <!-- Modern Bootstrap Navbar -->
    <nav class="navbar navbar-expand-lg mb-5 py-3 shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}" style="color: #1e293b !important; text-decoration: none;">
                <svg style="width: 36px; height: 36px;" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 10C6.477 10 2 14.477 2 20s4.477 10 10 10 10-4.477 10-10S17.523 10 12 10z" stroke="url(#paint0_linear)" stroke-width="4.5" stroke-linecap="round"/>
                    <path d="M28 10c-5.523 0-10 4.477-10 10s4.477 10 10 10 10-4.477 10-10-4.477-10-10-10z" stroke="url(#paint1_linear)" stroke-width="4.5" stroke-linecap="round"/>
                    <circle cx="20" cy="20" r="2.5" fill="#818CF8" />
                    <defs>
                        <linearGradient id="paint0_linear" x1="2" y1="10" x2="22" y2="30" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#10B981"/>
                            <stop offset="1" stop-color="#34D399"/>
                        </linearGradient>
                        <linearGradient id="paint1_linear" x1="18" y1="10" x2="38" y2="30" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#6366F1"/>
                            <stop offset="1" stop-color="#8B5CF6"/>
                        </linearGradient>
                    </defs>
                </svg>
                <span style="font-weight: 900; letter-spacing: -1px; font-size: 22px;">AF<span style="font-weight: 300; opacity: 0.4;">·</span>IYI</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item me-3">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="bi bi-arrow-left me-1"></i> Retour au site
                        </a>
                    </li>
                    <li class="nav-item dropdown ms-2">
                        <a class="nav-link d-flex align-items-center bg-light rounded-pill px-3 py-2 border text-decoration-none" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-5 me-2 text-secondary"></i>
                            <span class="fw-bold text-dark">{{ Auth::user()->name ?? 'Vendeur' }}</span>
                            <i class="bi bi-chevron-down ms-2 small text-secondary"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3">
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('profile.show') }}">
                                    <i class="bi bi-person me-2"></i>Mon Profil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="container pb-5">
        {{ $slot }}
    </main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <x-quick-nav />
    @stack('modals')
    @livewireScripts
</body>
</html>
