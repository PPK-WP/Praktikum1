<x-app-layout>
    <x-slot name="header">
        <h4 class="mb-0">Profile</h4>
    </x-slot>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Informasi Profil</h5>

                    @if (session('status') === 'profile-updated')
                        <div class="alert alert-success py-2">Profil tersimpan.</div>
                    @endif

                    <form method="POST" action="{{ url('/profile') }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}"
                                   class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}"
                                   class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Ubah Password</h5>

                    @if (session('status') === 'password-updated')
                        <div class="alert alert-success py-2">Password diperbarui.</div>
                    @endif

                    <form method="POST" action="{{ url('/password') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <input id="current_password" type="password" name="current_password" autocomplete="current-password"
                                   class="form-control @error('current_password', 'updatePassword') is-invalid @enderror">
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <input id="password" type="password" name="password" autocomplete="new-password"
                                   class="form-control @error('password', 'updatePassword') is-invalid @enderror">
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                   class="form-control" autocomplete="new-password">
                        </div>

                        <button type="submit" class="btn btn-primary">Ubah Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
