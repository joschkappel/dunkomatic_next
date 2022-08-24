<div class="card card-outline card-success">
    <div class="card-header">
        <h4 class="card-title font-weight-bold pt-2"><i class="fas fa-envelope text-success mx-2"></i>{{ trans_choice('message.message',2) }}</h4>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
<!-- Main node for this component -->

        <div id="accordion">
            @empty ($msglist)
            <div class="card-text">
                <span class="bg-info">{{__('message.message.empty')}}</span>
            </div>
            @endempty
            @foreach ( $msglist as $m)
            <div class="card">
                <div class="card-header" id="heading{{$m->id}}">
                    <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapse{{$m->id}}" aria-expanded="true" aria-controls="collapse{{$m->id}}">
                        {{ \Carbon\Carbon::parse($m->created_at)->locale( app()->getLocale() )->isoFormat('L, LT') }} {{ isset($m->data['sender']) ? '('.$m->data['sender'].'): ' : ': ' }} {!! Str::title($m->data['subject']) !!}
                    </button>
                    </h5>
                </div>
                <div id="collapse{{$m->id}}" class="collapse show" aria-labelledby="heading{{$m->id}}" data-parent="#accordion">
                    <div class="card-body">
                        {!! $m->data['greeting'] !!}
                        {!! $m->data['lines'] !!}
                        {!! $m->data['salutation'] !!}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
