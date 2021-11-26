@extends('layouts.page')

@section('content')
<x-card-form cardTitle="{{ __('schedule.title.calendar', ['region' => session('cur_region')->name ]) }}" :omitSubmit="true" colWidth="10">
  <div id='calendar'></div>
</x-card-form>
@endsection

@section('js')
  <script>
          $(function() {
            $('#frmClose').click(function(e){
                history.back();
            });
          });
        document.addEventListener('DOMContentLoaded', function() {
          var calendarEl = document.getElementById('calendar');
          var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'listYear',
                locale: '{{ app()->getLocale()}}',
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
                events: '{{ route('schedule_event.list-cal', ['region'=>session('cur_region')->id])}}',
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
