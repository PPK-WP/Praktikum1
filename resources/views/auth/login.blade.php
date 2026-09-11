<x-guest-layout>
    <h5 class="mb-3">Login</h5>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ url('/login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror" required autofocus autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 form-check">
            <input id="remember" type="checkbox" name="remember" class="form-check-input">
            <label for="remember" class="form-check-label">Ingat saya</label>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ url('/forgot-password') }}" class="small">Lupa password?</a>
            <button type="submit" class="btn btn-primary">Login</button>
        </div>
    </form>

    <hr>
    <p class="small text-muted mb-0 text-center">Belum punya akun? Hubungi Admin — registrasi mandiri ditutup.</p>
</x-guest-layout>
