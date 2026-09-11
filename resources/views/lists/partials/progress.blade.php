@php
    $totalTasks = $list->tasks->count();
    $doneTasks = $list->tasks->where('status', 'done')->count();
    $todoTasks = $list->tasks->where('status', 'todo')->count();
    $percentage = $list->progressPercentage();
@endphp

<div class="mb-4">
    <div class="d-flex justify-content-between mb-1">
        <span class="text-muted small">
            @if($totalTasks === 0)
                Belum ada task
            @elseif($percentage === 100)
                Selesai semua!
            @else
                Selesai {{ $doneTasks }} &middot; Belum {{ $todoTasks }} &middot; Total {{ $totalTasks }} ({{ $percentage }}%)
            @endif
        </span>
    </div>
    
    @if($totalTasks > 0)
        <div class="progress" style="height: 20px;">
            <div class="progress-bar {{ $percentage === 100 ? 'bg-success' : 'bg-primary' }}" 
                 role="progressbar" 
                 style="width: {{ $percentage }}%;" 
                 aria-valuenow="{{ $percentage }}" 
                 aria-valuemin="0" 
                 aria-valuemax="100">
                {{ $percentage }}%
            </div>
        </div>
    @endif
</div>
