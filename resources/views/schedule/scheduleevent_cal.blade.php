@extends('adminlte::page')

@section('content')

  <h3>Calendar</h3>

  <div id='calendar'></div>
@stop

@section('footer')
jochenk
@stop

@section('css')
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />
@stop

@section('js')
  <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>

  </script>
  <script>
      $(document).ready(function() {
          // page is now ready, initialize the calendar...
          $('#calendar').fullCalendar({
              // put your options and callbacks here
              defaultView: 'listMonth',
              themeSystem: 'bootstrap4',
              header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,listMonth'
                  },
              weekNumbers: true,
              timeZone: 'UTC',
              firstDay: 1,
              businessHours: {
                // days of week. an array of zero-based day of week integers (0=Sunday)
                daysOfWeek: [ 1,2,3,4,5,6,7 ], // Monday - Thursday

                startTime: '10:00', // a start time (10am in this example)
                endTime: '20:00', // an end time (6pm in this example)
              },
              // hiddenDays: [ 1,2,3,4,5],
              events: '{{ url('schedule_event/list-cal')}}',
              // events : [
              //     {
              //         title : 'an event',
              //         start : '2020-06-06T12:30:00',
              //         allDay : false,
              //     },
              //     {
              //         title : 'event  3',
              //         start : '2020-06-08T10:30:00',
              //         end: '2020-06-08T15:30:00',
              //     },
              // ],
              eventColor: 'green',
              eventTextColor: 'white',
          })


      });
  </script>


@stop
