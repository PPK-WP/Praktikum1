@php
    $actionLabels = [
        'created' => 'membuat task',
        'updated' => 'memperbarui task',
        'completed' => 'menandai selesai task',
        'deleted' => 'menghapus task',
    ];

    $actionBadges = [
        'created' => 'bg-primary',
        'updated' => 'bg-secondary',
        'completed' => 'bg-success',
        'deleted' => 'bg-danger',
    ];
@endphp

<div class="card shadow-sm mt-4">
    <div class="card-header">
        <h6 class="mb-0">Aktivitas Terbaru</h6>
    </div>

    <div class="card-body">
        @forelse ($list->activities as $activity)
            <div class="d-flex justify-content-between align-items-start border-bottom py-2">
                <div>
                    <strong>{{ $activity->user?->name ?? '—' }}</strong>
                    {{ $actionLabels[$activity->action] ?? $activity->action }}
                    <span>{{ $activity->task_title ?? $activity->task?->title ?? '—' }}</span>
                </div>

                <div class="text-end ms-3">
                    <span class="badge {{ $actionBadges[$activity->action] ?? 'bg-secondary' }}">
                        {{ $activity->action }}
                    </span>
                    <small class="d-block text-muted">
                        {{ $activity->created_at->diffForHumans() }}
                    </small>
                </div>
            </div>
        @empty
            <p class="text-muted mb-0">Belum ada aktivitas di workspace ini.</p>
        @endforelse
    </div>
</div>
