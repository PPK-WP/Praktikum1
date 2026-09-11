<form action="{{ url('/tasks/'.$task->id.'/toggle') }}" method="POST" class="d-inline m-0 p-0">
    @csrf
    @method('PATCH')
    @if($task->status === 'todo')
        <button type="submit" class="btn btn-success btn-sm" title="Tandai Selesai">
            ✔
        </button>
    @else
        <button type="submit" class="btn btn-secondary btn-sm" title="Batalkan Selesai">
            ↺
        </button>
    @endif
</form>
