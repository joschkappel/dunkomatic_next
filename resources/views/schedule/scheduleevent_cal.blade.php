@extends('adminlte::page')

@section('css')
<link rel='stylesheet' href='{{ URL::asset('vendor/fullcalendar/main.css') }}' />

@endsection

@section('content')

  <h3>@lang('schedule.title.calendar', ['region' => Auth::user()->region ])</h3>

  <div id='calendar'></div>
@stop

@section('footer')
jochenk
@stop


@section('js')
  {{-- <script src="//code.jquery.com/jquery-1.11.3.min.js"></script> --}}
  <script src='{{ URL::asset('vendor/fullcalendar/main.js') }}'></script>
  @if ( app()->getLocale() == 'de') <script src='{{ URL::asset('vendor/fullcalendar/locales/de.js') }}'></script>
  @elseif ( app()->getLocale() == 'en') <script src='{{ URL::asset('vendor/fullcalendar/locales/en.js') }}'></script>
  @endif
  <script src='{{ URL::asset('vendor/moment/moment.min.js') }}'></script>


  </script>
  <script>
        document.addEventListener('DOMContentLoaded', function() {
          var calendarEl = document.getElementById('calendar');
          var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'listYear',
                locale: '{{app()->getLocale()}}',
                themeSystem: 'bootstrap',
                headerToolbar: {
                      left: 'prev,next today',
                      center: 'title',
                      right: 'dayGridMonth,timeGridWeek,timeGridDay,listYear'
                    },
                weekNumbers: true,
                timeZone: 'UTC',
                businessHours: [{
                  daysOfWeek: [ 1,2,3,4,5 ], // Monday - Thursday
                  startTime: '16:00', // a start time (10am in this example)
                  endTime: '20:00', // an end time (6pm in this example)
                },{
                  daysOfWeek: [ 6,7 ], // Sat Sun
                  startTime: '9:00', // a start time (10am in this example)
                  endTime: '20:00', // an end time (6pm in this example)
                }],
                events: '{{ url('schedule_event/list-cal')}}',
                eventColor: 'green',
                eventTextColor: 'white',
                slotLabelFormat: {
                  hour: 'numeric',
                  minute: '2-digit',
                  hour12: false,
                },
          });
          calendar.render();
        });

  </script>


@stop
