{{-- Navigasi memakai URL literal (bukan route()) agar aman diuji per branch. Tidak ada link Register. --}}
<nav class="navbar navbar-expand-md navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/dashboard">JARA</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('lists*') ? 'active' : '' }}" href="/lists">My Workspace</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('mytasks*') ? 'active' : '' }}" href="/mytasks">Tugas Saya</a>
                </li>
                @if (auth()->user()?->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="/admin/users">User Management</a>
                    </li>
                @endif
            </ul>

            @auth
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ auth()->user()->name }}
                            @if (auth()->user()->isAdmin())
                                <span class="badge bg-warning text-dark ms-1">Admin</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/profile">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ url('/logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            @endauth
        </div>
    </div>
</nav>
