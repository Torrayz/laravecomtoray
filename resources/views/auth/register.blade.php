<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin — Torray Store</title>
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
                <p class="text-muted small mt-1">Create the initial store owner account.</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Full Name">
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="owner@example.com">
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required minlength="8" autocomplete="new-password" placeholder="Minimum 8 characters">
                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-4">
                    <label for="password-confirm" class="form-label">Confirm Password</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required minlength="8" autocomplete="new-password" placeholder="Repeat password">
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Sign Up</button>
            </form>

            <div class="text-center mt-4 small">
                <span class="text-muted">Already have an account?</span>
                <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Log in</a>.
            </div>
        </div>

        <div class="tr-auth-visual d-none d-lg-flex">
            <div style="max-width: 460px;">
                <span class="badge bg-light text-dark mb-3">Initial Setup</span>
                <h2 class="fw-bold display-6">Launch Your Storefront in Minutes.</h2>
                <p class="text-white-50 mt-2">Registration is automatically closed after the first store owner account is created.</p>
            </div>
        </div>
    </div>
</body>
</html>
