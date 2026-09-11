<div class="card shadow-sm mt-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">Anggota</h5>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger mb-3">{{ session('error') }}</div>
        @endif

        <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    {{ $list->owner->name }} ({{ $list->owner->email }})
                    <span class="badge bg-primary ms-2">OWNER</span>
                </div>
            </li>
            @foreach($list->members as $member)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        {{ $member->name }} ({{ $member->email }})
                    </div>
                    @if(auth()->id() === $list->owner_id)
                        <form action="{{ url('/lists/'.$list->id.'/members/'.$member->id) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Hapus anggota ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>

        @if(auth()->id() === $list->owner_id)
            <form action="{{ url('/lists/'.$list->id.'/members') }}" method="POST" class="d-flex">
                @csrf
                <input type="email" name="email" class="form-control me-2" placeholder="Email user..." required>
                <button type="submit" class="btn btn-outline-primary">Undang</button>
            </form>
        @endif
    </div>
</div>
