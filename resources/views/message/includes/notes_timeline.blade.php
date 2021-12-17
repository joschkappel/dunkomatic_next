<div class="card card-outline card-info">
    <div class="card-header">
        <h4 class="card-title font-weight-bold pt-2"><i class="fas fa-info text-info mx-2"></i>{{ __('message.note') }}</h4>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
            @forelse ($infos as $i)
            <div class="col-sm-12">
                <div class="alert alert-{{$i['msg_color']}}" role="alert">{!! $i['msg'] !!}
                </div>
            </div>
            @empty
            <div class="col-sm-12">
                <div class="alert alert-info" role="alert">{{__('message.note.empty')}}</div>
            </div>
            @endforelse
        <!-- The last icon means the story is complete -->
    </div>
</div>
