@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<style>
   /* body {
    margin: 40px 10px;
    padding: 0;
    font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
    font-size: 14px;
  } */

  #calendar {
  max-width: 1100px;
  margin: 40px auto;
}

.popper,
.tooltip {
  position: absolute;
  z-index: 9999;
  background: black;
  color: black;
  width: 150px;
  border-radius: 3px;
  box-shadow: 0 0 2px rgba(0,0,0,0.5);
  padding: 10px;
  text-align: center;
}
.style5 .tooltip {
  background: #1E252B;
  color: #FFFFFF;
  max-width: 200px;
  width: auto;
  font-size: .8rem;
  padding: .5em 1em;
}
.popper .popper__arrow,
.tooltip .tooltip-arrow {
  width: 0;
  height: 0;
  border-style: solid;
  position: absolute;
  margin: 5px;
}

.tooltip .tooltip-arrow,
.popper .popper__arrow {
  border-color: #FFC107;
}
.style5 .tooltip .tooltip-arrow {
  border-color: #1E252B;
}
.popper[x-placement^="top"],
.tooltip[x-placement^="top"] {
  margin-bottom: 5px;
}
.popper[x-placement^="top"] .popper__arrow,
.tooltip[x-placement^="top"] .tooltip-arrow {
  border-width: 5px 5px 0 5px;
  border-left-color: transparent;
  border-right-color: transparent;
  border-bottom-color: transparent;
  bottom: -5px;
  left: calc(50% - 5px);
  margin-top: 0;
  margin-bottom: 0;
}
.popper[x-placement^="bottom"],
.tooltip[x-placement^="bottom"] {
  margin-top: 5px;
}
.tooltip[x-placement^="bottom"] .tooltip-arrow,
.popper[x-placement^="bottom"] .popper__arrow {
  border-width: 0 5px 5px 5px;
  border-left-color: transparent;
  border-right-color: transparent;
  border-top-color: transparent;
  top: -5px;
  left: calc(50% - 5px);
  margin-top: 0;
  margin-bottom: 0;
}
.tooltip[x-placement^="right"],
.popper[x-placement^="right"] {
  margin-left: 5px;
}
.popper[x-placement^="right"] .popper__arrow,
.tooltip[x-placement^="right"] .tooltip-arrow {
  border-width: 5px 5px 5px 0;
  border-left-color: transparent;
  border-top-color: transparent;
  border-bottom-color: transparent;
  left: -5px;
  top: calc(50% - 5px);
  margin-left: 0;
  margin-right: 0;
}
.popper[x-placement^="left"],
.tooltip[x-placement^="left"] {
  margin-right: 5px;
}
.popper[x-placement^="left"] .popper__arrow,
.tooltip[x-placement^="left"] .tooltip-arrow {
  border-width: 5px 0 5px 5px;
  border-top-color: transparent;
  border-right-color: transparent;
  border-bottom-color: transparent;
  right: -5px;
  top: calc(50% - 5px);
  margin-left: 0;
  margin-right: 0;
}


</style>
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Trainings')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 550px">

  @include('Templates.alert_message')

  <div class="alert_message_js alert text-info fade show d-none" role="alert">
    <span id="alert_message_js"></span>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
  </div>


   <div class="card">
      <div class="card-body">
         <div id='calendar'></div>
      </div>
   </div>

   <hr>

</div>

   <textarea name="" id="clean_trainigs_data" cols="30" rows="10" hidden><?php echo json_encode($clean_trainigs)?></textarea>

<!-- /.container-fluid -->
@endsection
{{-- END CONTENT --}}
{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')
<script src="{{ asset('uidesign/vendor/calendar/dist/index.global.js') }}"></script>
<script src='https://unpkg.com/popper.js/dist/umd/popper.min.js'></script>
<script src='https://unpkg.com/tooltip.js/dist/umd/tooltip.min.js'></script>

@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
{{-- <script src="{{ asset('uidesign/js/custom/shift.js') }}"></script> --}}
<script>


   document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');
      var clean_trainigs = $('#clean_trainigs_data').val();
      clean_trainigs = JSON.parse(clean_trainigs);

      var calendar = new FullCalendar.Calendar(calendarEl, {
         initialDate: "{{ date('Y-m-d') }}",
         initialView: 'dayGridMonth',
         eventDidMount: function(info) {
            $(info.el).tooltip({ 
            title: info.event.extendedProps.description,
            placement: "top",
            trigger: "hover",
            container: "body"
            });
         },

         editable: true,
         selectable: true,
         businessHours: true,
         dayMaxEvents: true, // allow "more" link when too many events
         events: clean_trainigs,
         // timeFormat: 'H(:mm)',
         // events: [
         //    {
         //       title: 'All Day Event',
         //       start: '2023-01-01'
         //    },
         //    {
         //       title: 'Long Event',
         //       start: '2023-01-07',
         //       end: '2023-01-10'
         //    },
         //    {
         //       groupId: 999,
         //       title: 'Repeating Event',
         //       start: '2023-01-09T16:00:00'
         //    },
         //    {
         //       groupId: 999,
         //       title: 'Repeating Event',
         //       start: '2023-01-16T16:00:00'
         //    },
         //    {
         //       title: 'Conference',
         //       start: '2023-01-11',
         //       end: '2023-01-13'
         //    },
         //    {
         //       title: 'Meeting',
         //       start: '2023-01-12T10:30:00',
         //       end: '2023-01-12T12:30:00'
         //    },
         //    {
         //       title: 'Lunch',
         //       start: '2023-01-12T12:00:00'
         //    },
         //    {
         //       title: 'Meeting',
         //       start: '2023-01-12T14:30:00'
         //    },
         //    {
         //       title: 'Happy Hour',
         //       start: '2023-01-12T17:30:00'
         //    },
         //    {
         //       title: 'Dinner',
         //       start: '2023-01-12T20:00:00'
         //    },
         //    {
         //       title: 'Birthday Party',
         //       start: '2023-01-13T07:00:00'
         //    },
         //    {
         //       title: 'Click for Google',
         //       url: 'http://google.com/',
         //       start: '2023-01-28'
         //    }
         // ]
      });

      calendar.render();
   });
</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}