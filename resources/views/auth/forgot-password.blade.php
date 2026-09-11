<x-guest-layout>
    <h5 class="mb-2">Lupa Password</h5>
    <p class="small text-muted">Masukkan email akunmu, kami kirimkan link untuk mengatur ulang password.</p>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ url('/forgot-password') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ url('/login') }}" class="small">Kembali ke login</a>
            <button type="submit" class="btn btn-primary">Kirim Link Reset</button>
        </div>
    </form>
</x-guest-layout>
