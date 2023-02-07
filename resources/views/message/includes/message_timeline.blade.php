<div class="card card-outline card-success">
    <div class="card-header">
        <h4 class="card-title font-weight-bold pt-2"><i
                class="fas fa-envelope text-success mx-2"></i>{{ trans_choice('message.message', 2) }}</h4>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <!-- Main node for this component -->

        <div id="accordion">
            @empty($msglist)
                <div class="card-text">
                    <span class="bg-info">{{ __('message.message.empty') }}</span>
                </div>
            @endempty
            @foreach ($msglist as $m)
                <div class="card">
                    <div class="card-header" id="heading{{ $loop->index }}">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse"
                                data-target="#collapse{{ $loop->index }}" aria-expanded="true"
                                aria-controls="collapse{{ $loop->index }}">
                                {{ \Carbon\Carbon::parse($m->created_at)->locale(app()->getLocale())->isoFormat('L, LT') }}
                                {{ isset($m->data['sender']) ? '(' . $m->data['sender'] . '): ' : ': ' }}
                                {!! Str::title($m->data['subject']) !!}
                            </button>
                        </h5>
                    </div>
                    <div id="collapse{{ $loop->index }}" class="collapse @if ($loop->first) show @endif"
                        aria-labelledby="heading{{ $loop->index }}" data-parent="#accordion">
                        <div class="card-body">
                            {!! $m->data['greeting'] !!}
                            {!! $m->data['lines'] !!}
                            {!! $m->data['salutation'] !!}
                        </div>
                        <div class="card-footer">
                            @empty($m->data['tag'])
                                <button type="button" class="btn btn-danger btn-sm" id="btnMarkUnread"
                                    data-msg-id="{{ $m->id }}">{{ __('message.delete') }}</button>
                            @endempty
                            @if ($m->data['attachment'] ?? false)
                                <a type="button" target="_blank" class="btn btn-secondary btn-sm"
                                    href="{{ route('message.attachment', ['message' => $m->data['tag']]) }}"
                                    id="btnGetAttachment">{{ __('message.attachment.get') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
