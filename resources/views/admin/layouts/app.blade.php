<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin | Glamer')</title>
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    @stack('styles')
</head>

<body class="admin-body">
    <div class="admin-shell" id="adminShell">
        <aside class="admin-sidebar" id="adminSidebar">
            <div>
                <div class="admin-sidebar__brand mb-3">
                    <img src="{{ asset('assets/img/logo.svg') }}" alt="Glamer" width="48" class="rounded-3" style="background: rgba(255,255,255,0.08); padding: 8px;">
                    <div>
                        <strong>Glamer Admin</strong>
                        <p class="admin-sidebar__tagline mb-0">Curate drops, empower stories.</p>
                    </div>
                </div>

                @php
                    $adminUser = auth()->user();
                    $isFullAdmin = $adminUser?->isFullAdmin();
                @endphp

                <nav>
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="flaticon-home"></i> Overview
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <i class="flaticon-shopping-bag"></i> Products
                    </a>
                    @if($isFullAdmin)
                        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="flaticon-user"></i> Users
                        </a>
                        <a href="{{ route('admin.brands.index') }}" class="{{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                            <i class="flaticon-sparkle"></i> Brands
                        </a>
                        <a href="{{ route('admin.announcements.index') }}" class="{{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                            <i class="flaticon-star"></i> Alerts
                        </a>
                        <a href="{{ route('admin.coupons.index') }}" class="{{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                            <i class="flaticon-price-tag-1"></i> Coupons
                        </a>
                        <a href="{{ route('admin.blog.index') }}" class="{{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                            <i class="flaticon-blogging"></i> Blog
                        </a>
                        <a href="{{ route('admin.logs') }}" class="{{ request()->routeIs('admin.logs') ? 'active' : '' }}">
                            <i class="flaticon-warning"></i> Logs
                        </a>
                    @endif
                </nav>
            </div>

            <form action="{{ route('admin.logout') }}" method="POST" class="mt-auto">
                @csrf
                <button class="ul-btn w-100" style="background: var(--admin-accent); border: none;">Sign out</button>
            </form>
        </aside>

        <div class="admin-content">
            <div class="admin-topbar">
                <button class="admin-sidebar-toggle" id="adminSidebarToggle" type="button" aria-label="Toggle sidebar">
                    <span class="admin-sidebar-toggle__icon"></span>
                    Menu
                </button>
                <div class="admin-topbar__meta">
                    <span class="admin-topbar__label">Control Studio</span>
                    <span class="admin-topbar__title">@yield('page-title', 'Dashboard')</span>
                </div>
                <div class="admin-topbar__actions">
                    <span class="admin-topbar__badge">Glamer Collective</span>
                    @php
                        $adminName = optional($adminUser)->name ?? 'Glam Lead';
                        $adminInitial = strtoupper(substr($adminName, 0, 1) ?: 'G');
                    @endphp
                    <div class="admin-profile">
                        <div class="admin-profile__avatar">{{ $adminInitial }}</div>
                        <div>
                            <div class="admin-profile__name">{{ $adminName }}</div>
                            <div class="admin-profile__role">{{ $adminUser?->isFullAdmin() ? 'Full admin' : ($adminUser?->isProductAdmin() ? 'Product admin' : 'Admin') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-backdrop" id="adminBackdrop"></div>

            <main class="admin-main">
                @if(session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="{{ asset('assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script>
        (function () {
            const shell = document.getElementById('adminShell');
            const toggle = document.getElementById('adminSidebarToggle');
            const backdrop = document.getElementById('adminBackdrop');

            if (!shell || !toggle) {
                return;
            }

            const isDesktop = () => window.innerWidth >= 992;

            const openSidebar = () => {
                shell.classList.add('is-sidebar-open');
                if (!isDesktop() && backdrop) {
                    backdrop.classList.add('is-active');
                    document.body.style.overflow = 'hidden';
                }
            };

            const closeSidebar = () => {
                shell.classList.remove('is-sidebar-open');
                if (backdrop) {
                    backdrop.classList.remove('is-active');
                }
                if (!isDesktop()) {
                    document.body.style.overflow = '';
                }
            };

            toggle.addEventListener('click', () => {
                if (shell.classList.contains('is-sidebar-open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });

            if (backdrop) {
                backdrop.addEventListener('click', closeSidebar);
            }

            if (isDesktop()) {
                openSidebar();
            }

            window.addEventListener('resize', () => {
                if (isDesktop()) {
                    document.body.style.overflow = '';
                    backdrop?.classList.remove('is-active');
                    shell.classList.add('is-sidebar-open');
                } else {
                    closeSidebar();
                }
            });
        })();
    </script>
    @stack('scripts')
</body>

</html>
