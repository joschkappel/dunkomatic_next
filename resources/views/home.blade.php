@extends('layouts.page')

@section('content')
    <div class="row">
        <div class="col-md-6">
            @include('message.includes.reminders_timeline')
        </div>
        <div class="col-md-6">
            @include('message.includes.notes_timeline')
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @include('message.includes.message_timeline')
            @include('message.includes.message_show')
        </div>
    </div>
@stop

@section('css')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('js')
    <script> console.log('Hi there! going Dunkomatic now'); </script>
    <script>
        $(document).on('click', '#btnShowMessage', function () {
            $('#modalShowMessageTitle').html( $(this).data("title") );
            $('#msgSalutation').html( $(this).data("salutation") );
            $('#msgBody').html( $(this).data("body") );
            $('#msgGreeting').html( $(this).data("greeting") );
            $('#modalShowMessage').modal('show');
        });
    </script>
@stop
