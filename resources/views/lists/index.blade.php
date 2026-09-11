<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">My Workspace</h4>
            <a href="/lists/create" class="btn btn-primary btn-sm">+ Buat Workspace</a>
        </div>
    </x-slot>

    @if ($ownedLists->count() > 0)
        <h6 class="text-muted mb-3">Workspace Saya (Owner)</h6>
        <div class="row g-3 mb-4">
            @foreach ($ownedLists as $list)
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-1">
                                <a href="/lists/{{ $list->id }}" class="text-decoration-none text-dark">{{ $list->name }}</a>
                            </h5>
                            <small class="text-muted">{{ $list->tasks_count }} task</small>
                            <span class="badge bg-primary ms-1">Owner</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if ($memberLists->count() > 0)
        <h6 class="text-muted mb-3">Workspace Keanggotaan (Member)</h6>
        <div class="row g-3 mb-4">
            @foreach ($memberLists as $list)
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-1">
                                <a href="/lists/{{ $list->id }}" class="text-decoration-none text-dark">{{ $list->name }}</a>
                            </h5>
                            <small class="text-muted">{{ $list->tasks_count }} task</small>
                            <span class="badge bg-secondary ms-1">Member</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if ($ownedLists->count() === 0 && $memberLists->count() === 0)
        <div class="text-center py-5">
            <p class="text-muted">Belum ada workspace. Klik tombol "Buat Workspace" untuk memulai.</p>
        </div>
    @endif
</x-app-layout>
