<div class="card card-outline card-info collapsed-card">
    <div class="card-header">
        <h4 class="card-title font-weight-bold pt-2"><i class="fas fa-info text-info mx-2"></i>{{ __('message.withdrawal') }}</h4>
        <div class="card-tools">
            @if (count($withdrawals)!=null)
                <span class="badge badge-info text-md">{{ count($withdrawals) }}</span>
            @endif
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
        </div>
         <!-- /.card-tools -->
    </div>
    <!-- /.card-header -->
    <div class="card-body">
            @isset($withdrawals)
                @foreach ($withdrawals as $i)
                <div class="col">
                    <div class="alert alert-{{$i['msg_color']}} text-sm" role="alert">{!! $i['msg'] !!}
                    </div>
                </div>
                @endforeach
            @endisset
            @empty($withdrawals)
            <div class="col">
                <div class="alert alert-info tex-sm" role="alert">{{__('message.note.empty')}}</div>
            </div>
            @endempty
        <!-- The last icon means the story is complete -->
    </div>
</div>
