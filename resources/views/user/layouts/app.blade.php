<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Glamer')</title>
    <link rel="stylesheet" href="{{ asset('assets/icon/flaticon_glamer.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/user-dashboard.css') }}">
    @stack('styles')
</head>
<body class="user-dashboard-body">
    <div class="user-dashboard-shell" id="userDashboardShell">
        <aside class="user-dashboard-sidebar" id="userDashboardSidebar">
            <div class="user-dashboard-sidebar__brand">
                <img src="{{ asset('assets/img/logo.svg') }}" alt="Glamer" width="48" class="rounded-3">
                <div>
                    <strong>Hey, {{ auth()->user()->first_name }}!</strong>
                    <p class="user-dashboard-sidebar__tagline">Your personal fashion HQ</p>
                </div>
            </div>

            <nav class="user-dashboard-nav">
                <a href="{{ route('account.dashboard') }}" class="{{ request()->routeIs('account.dashboard') ? 'is-active' : '' }}">
                    <i class="flaticon-home"></i>
                    <span>Overview</span>
                </a>
                <a href="{{ route('wishlist') }}">
                    <i class="flaticon-heart"></i>
                    <span>Wishlist</span>
                </a>
                <a href="{{ route('cart') }}">
                    <i class="flaticon-shopping-bag"></i>
                    <span>Shopping Bag</span>
                </a>
                <a href="{{ route('shop') }}">
                    <i class="flaticon-up-right-arrow"></i>
                    <span>Shop New In</span>
                </a>
            </nav>

            <div class="user-dashboard-sidebar__footer">
                <a href="{{ route('home') }}" class="user-dashboard-sidebar__link">
                    <i class="flaticon-left-arrow"></i>
                    <span>Back to home</span>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="user-dashboard-sidebar__link user-dashboard-sidebar__link--danger">
                        <i class="flaticon-close"></i>
                        <span>Sign out</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="user-dashboard-backdrop" id="userDashboardBackdrop"></div>

        <div class="user-dashboard-content">
            <header class="user-dashboard-topbar">
                <button class="user-dashboard-topbar__toggle" id="userDashboardToggle" type="button" aria-label="Toggle menu">
                    <span></span>
                </button>
                <div class="user-dashboard-topbar__title">
                    <span class="label">Glamer account</span>
                    <h1 class="title">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="user-dashboard-topbar__actions">
                    <a href="{{ route('shop') }}" class="user-dashboard-pill">Discover new arrivals</a>
                    <div class="user-dashboard-profile">
                        <div class="user-dashboard-profile__avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="user-dashboard-profile__name">{{ auth()->user()->name }}</p>
                            <p class="user-dashboard-profile__meta">Member since {{ optional(auth()->user()->created_at)->format('Y') ?? '2025' }}</p>
                        </div>
                    </div>
                </div>
            </header>

            <main class="user-dashboard-main">
                @if(session('status'))
                    <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="{{ asset('assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script>
        (function () {
            const shell = document.getElementById('userDashboardShell');
            const toggle = document.getElementById('userDashboardToggle');
            const backdrop = document.getElementById('userDashboardBackdrop');

            if (! shell || ! toggle) {
                return;
            }

            const isDesktop = () => window.innerWidth >= 992;

            const open = () => {
                shell.classList.add('is-menu-open');

                if (!isDesktop() && backdrop) {
                    backdrop.classList.add('is-active');
                    document.body.style.overflow = 'hidden';
                }
            };

            const close = () => {
                shell.classList.remove('is-menu-open');

                if (backdrop) {
                    backdrop.classList.remove('is-active');
                }

                if (!isDesktop()) {
                    document.body.style.overflow = '';
                }
            };

            toggle.addEventListener('click', () => {
                if (shell.classList.contains('is-menu-open')) {
                    close();
                } else {
                    open();
                }
            });

            if (backdrop) {
                backdrop.addEventListener('click', close);
            }

            const syncWithViewport = () => {
                if (isDesktop()) {
                    shell.classList.add('is-menu-open');
                    if (backdrop) {
                        backdrop.classList.remove('is-active');
                    }
                    document.body.style.overflow = '';
                    return;
                }

                if (shell.classList.contains('is-menu-open')) {
                    if (backdrop) {
                        backdrop.classList.add('is-active');
                    }
                    document.body.style.overflow = 'hidden';
                } else {
                    if (backdrop) {
                        backdrop.classList.remove('is-active');
                    }
                    document.body.style.overflow = '';
                }
            };

            window.addEventListener('resize', () => {
                syncWithViewport();
            });

            syncWithViewport();
        })();
    </script>
    @stack('scripts')
</body>
</html>
