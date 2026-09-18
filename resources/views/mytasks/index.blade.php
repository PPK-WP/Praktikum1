<x-app-layout>
    <x-slot name="header">
        <h4 class="mb-0">Tugas Saya</h4>
    </x-slot>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="/mytasks" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label for="q" class="form-label small text-muted mb-1">Cari judul / deskripsi</label>
                    <input type="text" id="q" name="q" value="{{ $q }}" class="form-control" placeholder="mis. laporan">
                </div>
                <div class="col-6 col-md-2">
                    <label for="sort" class="form-label small text-muted mb-1">Urutkan</label>
                    <select id="sort" name="sort" class="form-select">
                        @foreach (['due_date' => 'Tenggat', 'title' => 'Judul', 'priority' => 'Prioritas', 'status' => 'Status'] as $value => $label)
                            <option value="{{ $value }}" {{ $sort === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label for="dir" class="form-label small text-muted mb-1">Arah</label>
                    <select id="dir" name="dir" class="form-select">
                        <option value="asc" {{ $dir === 'asc' ? 'selected' : '' }}>Naik (A→Z)</option>
                        <option value="desc" {{ $dir === 'desc' ? 'selected' : '' }}>Turun (Z→A)</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Cari</button>
                    @if ($q !== '')
                        <a href="/mytasks" class="btn btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if ($tasks->isEmpty())
        <div class="text-center py-5">
            <p class="text-muted mb-0">
                @if ($q !== '')
                    Tidak ditemukan task dengan kata kunci "<strong>{{ $q }}</strong>".
                @else
                    Kamu belum membuat tugas apa pun.
                @endif
            </p>
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Task</th>
                                <th>Workspace</th>
                                <th>Prioritas</th>
                                <th>Tenggat</th>
                                <th>Status</th>
                                <th>Pembuat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                                <tr>
                                    <td class="{{ $task->status === 'done' ? 'text-decoration-line-through text-success' : '' }}">
                                        {{ $task->title }}
                                        @if ($task->description)
                                            <br><small class="text-muted">{{ Str::limit($task->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="/lists/{{ $task->list_id }}" class="text-decoration-none">{{ $task->list?->name }}</a>
                                    </td>
                                    <td>
                                        @if ($task->priority === 'penting')
                                            <span class="badge bg-danger">Penting</span>
                                        @elseif ($task->priority === 'sedang')
                                            <span class="badge bg-warning text-dark">Sedang</span>
                                        @else
                                            <span class="badge bg-secondary">Rendah</span>
                                        @endif
                                    </td>
                                    <td class="{{ $task->due_date->lt(today()) ? 'text-danger fw-bold' : '' }}">
                                        {{ $task->due_date->format('d M Y') }}
                                    </td>
                                    <td>
                                        @if ($task->status === 'done')
                                            <span class="badge bg-success">Selesai</span>
                                        @else
                                            <span class="badge bg-light text-dark border">Belum</span>
                                        @endif
                                    </td>
                                    <td>{{ $task->creator?->name ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $tasks->links('pagination::bootstrap-5') }}
        </div>
    @endif
</x-app-layout>
