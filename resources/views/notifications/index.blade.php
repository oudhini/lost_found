@extends($layout)
@section('content')
<div class="container-fluid pb-5">
    @include('partials.flash')
    @if ($notifications->isEmpty())
        <div class="alert alert-info text-center">Aucune notification pour le moment.</div>
    @else
        <form method="POST" action="{{ route('notifications.read-all') }}" class="mb-3">
            @csrf @method('PATCH')
            <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-check2-all"></i> Tout marquer comme lu</button>
        </form>
        <ul class="list-group mb-3">
            @foreach ($notifications as $notification)
                <li class="list-group-item d-flex justify-content-between align-items-center {{ $notification->read_at ? '' : 'list-group-item-warning' }}">
                    <div>
                        <div>{{ $notification->data['message'] ?? 'Notification' }}</div>
                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}@if (! empty($notification->data['reporter'])) — par {{ $notification->data['reporter'] }}@endif</small>
                    </div>
                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-primary">Ouvrir</button>
                    </form>
                </li>
            @endforeach
        </ul>
        {{ $notifications->links() }}
    @endif
</div>
@endsection
