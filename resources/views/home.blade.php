@extends('layouts.page')

@section('content')
    @isset($links)
    <div class="row">
        <div class="col-md-12">
            @include('app.includes.quicklinks')
        </div>
    </div>
    @endisset
    <div class="row">
        <div class="col-md-6">
                @include('message.includes.reminders_timeline')
                @include('message.includes.notes_timeline')
        </div>
        <div class="col-md-6">
            @include('message.includes.message_timeline')
            @include('message.includes.message_show')
        </div>
    </div>

@endsection

@section('footer')
    @include('app.cookie_consent')
@endsection

@section('css')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('js')
    <script> console.log('Hi there! going Dunkomatic now'); </script>
    <script>
        function btnShowMessage(msg_id){
            console.log(msg_id);
            var url = '{{ route("notification.show",["notification"=>":notificationid:"]) }}';
            url = url.replace(':notificationid:', msg_id);

            $.ajax({
                type: 'GET',
                url: url,
                success: function (data) {
                    var note = JSON.parse(data);
                    $('#modalShowMessageTitle').html( note.subject );
                    $('#msgSalutation').html( note.salutation );
                    $('#msgBody').html( note.lines );
                    $('#msgGreeting').html( note.greeting );
                    $('#btnMarkUnread').data('msg-id', msg_id);
                    $('#modalShowMessage').modal('show');
                }
            });

        };
        $('#btnMarkUnread').on('click', function(data){
            console.log($(this).data('msg-id'));
            var url = "{{ route('message.mark_as_read', ['message'=>':msgid:'])}}";
            url = url.replace(':msgid:', $(this).data('msg-id'));

            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: 'POST'
                },
            });
        });
    </script>
@stop
