<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">{{ $list->name }}</h4>
            @if ($list->owner_id === auth()->id())
                <div class="d-flex gap-2">
                    <a href="/lists/{{ $list->id }}/edit" class="btn btn-outline-primary btn-sm">Edit</a>
                    <form method="POST" action="/lists/{{ $list->id }}" class="d-inline"
                          onsubmit="return confirm('Yakin ingin menghapus workspace ini? Semua task di dalamnya juga akan terhapus.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                    </form>
                </div>
            @endif
        </div>
    </x-slot>

    @includeIf('lists.partials.progress', ['list' => $list])

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h6>Tambah Task</h6>
            <form method="POST" action="/lists/{{ $list->id }}/tasks">
                @csrf
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               name="title" placeholder="Judul task" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <select class="form-select @error('priority') is-invalid @enderror" name="priority">
                            <option value="penting" {{ old('priority') === 'penting' ? 'selected' : '' }}>Penting</option>
                            <option value="sedang" {{ old('priority', 'sedang') === 'sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="rendah" {{ old('priority') === 'rendah' ? 'selected' : '' }}>Rendah</option>
                        </select>
                        @error('priority')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                               name="due_date" value="{{ old('due_date', date('Y-m-d')) }}" required>
                        @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control @error('description') is-invalid @enderror"
                               name="description" placeholder="Deskripsi (opsional)" value="{{ old('description') }}">
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if ($list->tasks->count() > 0)
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Task</th>
                            <th>Prioritas</th>
                            <th>Tenggat</th>
                            <th>Pembuat</th>
                            <th style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list->tasks as $task)
                            <tr>
                                <td class="{{ $task->status === 'done' ? 'text-decoration-line-through text-success' : '' }}">
                                    {{ $task->title }}
                                    @if ($task->description)
                                        <br><small class="text-muted">{{ Str::limit($task->description, 50) }}</small>
                                    @endif
                                    @includeIf('lists.partials.toggle', ['task' => $task])
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
                                <td class="{{ $task->due_date->isPast() ? 'text-danger fw-bold' : '' }}">
                                    {{ $task->due_date->format('d M Y') }}
                                </td>
                                <td>
                                    {{ $task->creator?->name ?? '—' }}
                                </td>
                                <td>
                                    <a href="/lists/{{ $list->id }}/tasks/{{ $task->id }}/edit" class="btn btn-outline-primary btn-sm">Edit</a>
                                    <form method="POST" action="/lists/{{ $list->id }}/tasks/{{ $task->id }}" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus task ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="text-center py-4">
            <p class="text-muted">Belum ada task. Tambahkan task pertamamu di atas.</p>
        </div>
    @endif

    @includeIf('lists.partials.activity', ['list' => $list])

    @includeIf('lists.partials.members', ['list' => $list])
</x-app-layout>
