<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Torray Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/torray-admin.css') }}">
</head>
<body>
    <div class="tr-auth-wrap">
        <div class="tr-auth-panel">
            <div class="mb-4">
                <a href="/" class="text-decoration-none text-dark fw-bold fs-4">Torray Store</a>
                <p class="text-muted small mt-1">Sign in to manage catalog, inventory, and customer orders.</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="admin@example.com">
                    </div>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-shield-lock"></i></span>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                    </div>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Log in</button>
            </form>

            @if(!\App\Models\User::exists())
                <div class="text-center mt-4 small">
                    <span class="text-muted">First-time setup?</span>
                    <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">Create admin account</a>.
                </div>
            @endif
        </div>

        <div class="tr-auth-visual d-none d-lg-flex">
            <div style="max-width: 460px;">
                <span class="badge bg-light text-dark mb-3">Merchant Console</span>
                <h2 class="fw-bold display-6">Inventory, Orders, and Storefront Control.</h2>
                <p class="text-white-50 mt-2">Clean Laravel 9 commerce platform with transactional checkout, stock verification, and responsive catalog management.</p>
            </div>
        </div>
    </div>
</body>
</html>
