<?php $__env->startSection('plugins.FullCalendar',true); ?>

<?php $__env->startSection('content'); ?>

  <h3><?php echo app('translator')->get('schedule.title.calendar', ['region' => session('cur_region')->name ]); ?></h3>

  <div id='calendar'></div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
  <script>
        document.addEventListener('DOMContentLoaded', function() {
          var calendarEl = document.getElementById('calendar');
          var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'listYear',
                locale: '<?php echo e(app()->getLocale(), false); ?>',
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
                events: '<?php echo e(route('schedule_event.list-cal', ['region'=>session('cur_region')->id]), false); ?>',
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


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/schedule/scheduleevent_cal.blade.php ENDPATH**/ ?>