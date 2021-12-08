<div class="card card-outline card-info">
    <div class="card-header">
        <h4 class="card-title font-weight-bold pt-2"><i class="fas fa-info text-info mx-2"></i>{{ __('message.note') }}</h4>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
            <ul class="list-group list-group-flush">
            @forelse ($infos as $i)
                <li class="list-group-item d-flex-sm justify-content-between align-items-center">
                    {{ $i['msg'] }}
                </li>
            @empty
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ __('message.note.empty')}}
                </li>
            @endforelse
        </ul>
        <!-- The last icon means the story is complete -->
    </div>
</div>
