{{-- Dashboard bawaan baseline. Redirect pasca-login per role (admin → /admin/users, user → /lists) dikerjakan P1 lewat DashboardController. --}}
<x-app-layout>
    <x-slot name="header">
        <h4 class="mb-0">Dashboard</h4>
    </x-slot>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Selamat datang, {{ auth()->user()->name }}!</h5>
            <p class="card-text text-muted">Kamu sudah login ke JARA.</p>
            <a href="/lists" class="btn btn-primary">Buka My Workspace</a>
            @if (auth()->user()->isAdmin())
                <a href="/admin/users" class="btn btn-outline-secondary">User Management</a>
            @endif
        </div>
    </div>
</x-app-layout>
