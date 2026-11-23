<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} — Store Console</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/torray-admin.css') }}">
    @yield('css')
</head>
<body>
    <div class="tr-admin-shell">
        <aside id="trSidebar" class="tr-sidebar">
            <div class="tr-sidebar-brand">
                <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 text-decoration-none text-white">
                    @if(Auth::user()->shop && Auth::user()->shop->path)
                        <img src="{{ asset('shop/' . Auth::user()->shop->path) }}" alt="Shop Logo">
                    @endif
                    <span class="fw-bold">{{ Auth::user()->shop->name_shop ?? 'Torray Store' }}</span>
                </a>
                <button type="button" class="btn btn-sm text-white d-lg-none" id="trSidebarClose" aria-label="Close sidebar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <ul class="tr-sidebar-nav">
                <li>
                    <a href="{{ route('home') }}" class="tr-sidebar-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('category') }}" class="tr-sidebar-link {{ request()->routeIs('category*') ? 'active' : '' }}">
                        <i class="bi bi-tags-fill"></i>
                        <span>Categories</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('products') }}" class="tr-sidebar-link {{ request()->routeIs('product*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam-fill"></i>
                        <span>Products</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('orders') }}" class="tr-sidebar-link {{ request()->routeIs('order*') ? 'active' : '' }}">
                        <i class="bi bi-bag-check-fill"></i>
                        <span>Orders</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('shopDetail') }}" class="tr-sidebar-link {{ request()->routeIs('shopDetail') ? 'active' : '' }}">
                        <i class="bi bi-shop"></i>
                        <span>Shop Settings</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('clientHome') }}" target="_blank" class="tr-sidebar-link">
                        <i class="bi bi-box-arrow-up-right"></i>
                        <span>View Storefront</span>
                    </a>
                </li>
            </ul>

            <div class="tr-sidebar-footer">
                <div class="small text-secondary mb-2 px-2">{{ Auth::user()->name }}</div>
                <button type="button" class="tr-sidebar-link w-100 border-0 bg-transparent text-start" data-bs-toggle="modal" data-bs-target="#modalUpdatePassword">
                    <i class="bi bi-shield-lock"></i>
                    <span>Change Password</span>
                </button>
                <a href="{{ route('logout') }}" class="tr-sidebar-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Log Out</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </aside>

        <div class="tr-main">
            <header class="d-flex justify-content-between align-items-center bg-white border rounded-3 px-4 py-3 mb-4">
                <div class="d-flex align-items-center gap-3">
                    <button type="button" class="btn btn-outline-secondary btn-sm d-lg-none" id="trSidebarOpen" aria-label="Open menu">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <h1 class="h4 m-0 fw-bold">{{ $title ?? 'Dashboard' }}</h1>
                </div>
                <div>
                    @yield('button')
                </div>
            </header>

            <main class="flex-grow-1">
                @yield('content')
            </main>

            <footer class="mt-5 pt-3 border-top d-flex flex-wrap justify-content-between align-items-center text-muted small">
                <span>&copy; {{ now()->year }} Torray Store</span>
                <span>Built by <a href="mailto:tripujiantoro12@gmail.com" class="text-decoration-none">Tri Puji Antoro</a></span>
            </footer>
        </div>
    </div>

    <div class="modal fade" id="modalUpdatePassword" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalUpdatePasswordLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUpdatePasswordLabel">Update Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('shopUpdatePassword') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required minlength="8" autocomplete="new-password" placeholder="Minimum 8 characters">
                            @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password-confirm" class="form-label">Confirm New Password</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required minlength="8" autocomplete="new-password" placeholder="Repeat password">
                        </div>
                        <button type="submit" class="btn btn-primary float-end">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/torray-ui.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('trSidebar');
            const openBtn = document.getElementById('trSidebarOpen');
            const closeBtn = document.getElementById('trSidebarClose');
            if (openBtn && sidebar) openBtn.addEventListener('click', () => sidebar.classList.add('is-open'));
            if (closeBtn && sidebar) closeBtn.addEventListener('click', () => sidebar.classList.remove('is-open'));
        });
    </script>
    @yield('js')
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                torrayToast(@json(session('success')), 'success');
            });
        </script>
    @endif
    @if(session('failed'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                torrayToast(@json(session('failed')), 'error');
            });
        </script>
    @endif
</body>
</html>
