@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<style>

  .list-timeline {
    margin: 0;
    padding: 5px 0;
    position: relative
  }

  .list-timeline:before {
    width: 1px;
    background: #ccc;
    position: absolute;
    left: 6px;
    top: 0;
    bottom: 0;
    height: 100%;
    content: ''
  }

  .list-timeline .list-timeline-item {
    margin: 0;
    padding: 0;
    padding-left: 24px !important;
    position: relative
  }

  .list-timeline .list-timeline-item:before {
    width: 12px;
    height: 12px;
    background: #fff;
    border: 2px solid #ccc;
    position: absolute;
    left: 0;
    top: 4px;
    content: '';
    border-radius: 100%;
    -webkit-transition: all .3 ease-in-out;
    transition: all .3 ease-in-out
  }

  .list-timeline .list-timeline-item[data-toggle=collapse] {
    cursor: pointer
  }

  .list-timeline .list-timeline-item.active:before,
  .list-timeline .list-timeline-item.show:before {
    background: #ccc
  }

  .list-timeline.list-timeline-light .list-timeline-item.active:before,
  .list-timeline.list-timeline-light .list-timeline-item.show:before,
  .list-timeline.list-timeline-light:before {
    background: #f8f9fa
  }

  .list-timeline .list-timeline-item.list-timeline-item-marker-middle:before {
    top: 50%;
    margin-top: -6px
  }

  .list-timeline.list-timeline-light .list-timeline-item:before {
    border-color: #f8f9fa
  }

  .list-timeline.list-timeline-grey .list-timeline-item.active:before,
  .list-timeline.list-timeline-grey .list-timeline-item.show:before,
  .list-timeline.list-timeline-grey:before {
    background: #e9ecef
  }

  .list-timeline.list-timeline-grey .list-timeline-item:before {
    border-color: #e9ecef
  }

  .list-timeline.list-timeline-grey-dark .list-timeline-item.active:before,
  .list-timeline.list-timeline-grey-dark .list-timeline-item.show:before,
  .list-timeline.list-timeline-grey-dark:before {
    background: #495057
  }

  .list-timeline.list-timeline-grey-dark .list-timeline-item:before {
    border-color: #495057
  }

  .list-timeline.list-timeline-primary .list-timeline-item.active:before,
  .list-timeline.list-timeline-primary .list-timeline-item.show:before,
  .list-timeline.list-timeline-primary:before {
    background: #55A79A
  }

  .list-timeline.list-timeline-primary .list-timeline-item:before {
    border-color: #55A79A
  }

  .list-timeline.list-timeline-primary-dark .list-timeline-item.active:before,
  .list-timeline.list-timeline-primary-dark .list-timeline-item.show:before,
  .list-timeline.list-timeline-primary-dark:before {
    background: #33635c
  }

  .list-timeline.list-timeline-primary-dark .list-timeline-item:before {
    border-color: #33635c
  }

  .list-timeline.list-timeline-primary-faded .list-timeline-item.active:before,
  .list-timeline.list-timeline-primary-faded .list-timeline-item.show:before,
  .list-timeline.list-timeline-primary-faded:before {
    background: rgba(85, 167, 154, .3)
  }

  .list-timeline.list-timeline-primary-faded .list-timeline-item:before {
    border-color: rgba(85, 167, 154, .3)
  }

  .list-timeline.list-timeline-info .list-timeline-item.active:before,
  .list-timeline.list-timeline-info .list-timeline-item.show:before,
  .list-timeline.list-timeline-info:before {
    background: #17a2b8
  }

  .list-timeline.list-timeline-info .list-timeline-item:before {
    border-color: #17a2b8
  }

  .list-timeline.list-timeline-success .list-timeline-item.active:before,
  .list-timeline.list-timeline-success .list-timeline-item.show:before,
  .list-timeline.list-timeline-success:before {
    background: #28a745
  }

  .list-timeline.list-timeline-success .list-timeline-item:before {
    border-color: #28a745
  }

  .list-timeline.list-timeline-warning .list-timeline-item.active:before,
  .list-timeline.list-timeline-warning .list-timeline-item.show:before,
  .list-timeline.list-timeline-warning:before {
    background: #ffc107
  }

  .list-timeline.list-timeline-warning .list-timeline-item:before {
    border-color: #ffc107
  }

  .list-timeline.list-timeline-danger .list-timeline-item.active:before,
  .list-timeline.list-timeline-danger .list-timeline-item.show:before,
  .list-timeline.list-timeline-danger:before {
    background: #dc3545
  }

  .list-timeline.list-timeline-danger .list-timeline-item:before {
    border-color: #dc3545
  }

  .list-timeline.list-timeline-dark .list-timeline-item.active:before,
  .list-timeline.list-timeline-dark .list-timeline-item.show:before,
  .list-timeline.list-timeline-dark:before {
    background: #343a40
  }

  .list-timeline.list-timeline-dark .list-timeline-item:before {
    border-color: #343a40
  }

  .list-timeline.list-timeline-secondary .list-timeline-item.active:before,
  .list-timeline.list-timeline-secondary .list-timeline-item.show:before,
  .list-timeline.list-timeline-secondary:before {
    background: #6c757d
  }

  .list-timeline.list-timeline-secondary .list-timeline-item:before {
    border-color: #6c757d
  }

  .list-timeline.list-timeline-black .list-timeline-item.active:before,
  .list-timeline.list-timeline-black .list-timeline-item.show:before,
  .list-timeline.list-timeline-black:before {
    background: #000
  }

  .list-timeline.list-timeline-black .list-timeline-item:before {
    border-color: #000
  }

  .list-timeline.list-timeline-white .list-timeline-item.active:before,
  .list-timeline.list-timeline-white .list-timeline-item.show:before,
  .list-timeline.list-timeline-white:before {
    background: #fff
  }

  .list-timeline.list-timeline-white .list-timeline-item:before {
    border-color: #fff
  }

  .list-timeline.list-timeline-green .list-timeline-item.active:before,
  .list-timeline.list-timeline-green .list-timeline-item.show:before,
  .list-timeline.list-timeline-green:before {
    background: #55A79A
  }

  .list-timeline.list-timeline-green .list-timeline-item:before {
    border-color: #55A79A
  }

  .list-timeline.list-timeline-red .list-timeline-item.active:before,
  .list-timeline.list-timeline-red .list-timeline-item.show:before,
  .list-timeline.list-timeline-red:before {
    background: #BE3E1D
  }

  .list-timeline.list-timeline-red .list-timeline-item:before {
    border-color: #BE3E1D
  }

  .list-timeline.list-timeline-blue .list-timeline-item.active:before,
  .list-timeline.list-timeline-blue .list-timeline-item.show:before,
  .list-timeline.list-timeline-blue:before {
    background: #00ADBB
  }

  .list-timeline.list-timeline-blue .list-timeline-item:before {
    border-color: #00ADBB
  }

  .list-timeline.list-timeline-purple .list-timeline-item.active:before,
  .list-timeline.list-timeline-purple .list-timeline-item.show:before,
  .list-timeline.list-timeline-purple:before {
    background: #b771b0
  }

  .list-timeline.list-timeline-purple .list-timeline-item:before {
    border-color: #b771b0
  }

  .list-timeline.list-timeline-pink .list-timeline-item.active:before,
  .list-timeline.list-timeline-pink .list-timeline-item.show:before,
  .list-timeline.list-timeline-pink:before {
    background: #CC164D
  }

  .list-timeline.list-timeline-pink .list-timeline-item:before {
    border-color: #CC164D
  }

  .list-timeline.list-timeline-orange .list-timeline-item.active:before,
  .list-timeline.list-timeline-orange .list-timeline-item.show:before,
  .list-timeline.list-timeline-orange:before {
    background: #e67e22
  }

  .list-timeline.list-timeline-orange .list-timeline-item:before {
    border-color: #e67e22
  }

  .list-timeline.list-timeline-lime .list-timeline-item.active:before,
  .list-timeline.list-timeline-lime .list-timeline-item.show:before,
  .list-timeline.list-timeline-lime:before {
    background: #b1dc44
  }

  .list-timeline.list-timeline-lime .list-timeline-item:before {
    border-color: #b1dc44
  }

  .list-timeline.list-timeline-blue-dark .list-timeline-item.active:before,
  .list-timeline.list-timeline-blue-dark .list-timeline-item.show:before,
  .list-timeline.list-timeline-blue-dark:before {
    background: #34495e
  }

  .list-timeline.list-timeline-blue-dark .list-timeline-item:before {
    border-color: #34495e
  }

  .list-timeline.list-timeline-red-dark .list-timeline-item.active:before,
  .list-timeline.list-timeline-red-dark .list-timeline-item.show:before,
  .list-timeline.list-timeline-red-dark:before {
    background: #a10f2b
  }

  .list-timeline.list-timeline-red-dark .list-timeline-item:before {
    border-color: #a10f2b
  }

  .list-timeline.list-timeline-brown .list-timeline-item.active:before,
  .list-timeline.list-timeline-brown .list-timeline-item.show:before,
  .list-timeline.list-timeline-brown:before {
    background: #91633c
  }

  .list-timeline.list-timeline-brown .list-timeline-item:before {
    border-color: #91633c
  }

  .list-timeline.list-timeline-cyan-dark .list-timeline-item.active:before,
  .list-timeline.list-timeline-cyan-dark .list-timeline-item.show:before,
  .list-timeline.list-timeline-cyan-dark:before {
    background: #008b8b
  }

  .list-timeline.list-timeline-cyan-dark .list-timeline-item:before {
    border-color: #008b8b
  }

  .list-timeline.list-timeline-yellow .list-timeline-item.active:before,
  .list-timeline.list-timeline-yellow .list-timeline-item.show:before,
  .list-timeline.list-timeline-yellow:before {
    background: #D4AC0D
  }

  .list-timeline.list-timeline-yellow .list-timeline-item:before {
    border-color: #D4AC0D
  }

  .list-timeline.list-timeline-slate .list-timeline-item.active:before,
  .list-timeline.list-timeline-slate .list-timeline-item.show:before,
  .list-timeline.list-timeline-slate:before {
    background: #5D6D7E
  }

  .list-timeline.list-timeline-slate .list-timeline-item:before {
    border-color: #5D6D7E
  }

  .list-timeline.list-timeline-olive .list-timeline-item.active:before,
  .list-timeline.list-timeline-olive .list-timeline-item.show:before,
  .list-timeline.list-timeline-olive:before {
    background: olive
  }

  .list-timeline.list-timeline-olive .list-timeline-item:before {
    border-color: olive
  }

  .list-timeline.list-timeline-teal .list-timeline-item.active:before,
  .list-timeline.list-timeline-teal .list-timeline-item.show:before,
  .list-timeline.list-timeline-teal:before {
    background: teal
  }

  .list-timeline.list-timeline-teal .list-timeline-item:before {
    border-color: teal
  }

  .list-timeline.list-timeline-green-bright .list-timeline-item.active:before,
  .list-timeline.list-timeline-green-bright .list-timeline-item.show:before,
  .list-timeline.list-timeline-green-bright:before {
    background: #2ECC71
  }

  .list-timeline.list-timeline-green-bright .list-timeline-item:before {
    border-color: #2ECC71
  }
</style>
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Biometric Logs')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 550px">

  @include('Templates.alert_message')

  <div class="card">
      <div class="card-body">
          <form action="{{ route('biometrics') }}" method="get">
              <div class="row">

                  <div class="col-lg-4 col-sm-12">
                      <div class="form-group">
                          <label class="control-label">Month</label>
                          <select id="month" name="month" class="form-control custom-select">
                              @php
                                  $monthSelected = $_GET['month'] ?? date('n');
                                  $yearSelected = $_GET['year'] ?? date('Y');
                              @endphp
                                  @foreach($months as $i => $month)
                                    <option value="{{ $i }}" <?php echo ($i == $monthSelected) ? 'selected':'' ?>>{{ $month }}</option>
                                  @endforeach
                          </select>
                      </div>
                  </div>

                  <div class="col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label class="control-label">Year</label>
                      <select id="year" name="year" class="form-control custom-select">
                              @foreach($years as $i => $year)
                              <option value="{{ $i }}" <?php echo ($i == $yearSelected) ? 'selected':'' ?>>{{ $year }}</option>
                              @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-4 col-sm-12">
                    <div class="form-group">
                        <label class="hide" style="visibility: hidden">Search Button</label>
                        @include('button_component.search_button', ['button_search_id' => "btn-search-biometrics"])
                        {{-- <button type="button" class="btn btn-primary mt-auto w-100" id="btn-search-biometrics"><i class="fa fa-search" aria-hidden="true"></i> Search</button> --}}
                    </div>
                  </div>
              </div>
          </form>
      </div>
  </div>

  <hr>
  
  <div class="card">
      <div class="card-body">
          <div class="container py-7">
              <h4 class="text-uppercase text-letter-spacing-xs my-0 text-primary font-weight-bold">
                Biometric Logs
              </h4>
              <p class="text-sm text-dark mt-0 mb-5">There's time and place for everything.</p>

              <!-- Days -->
              <div class="row">
                  @php
                      $date_indicator = '';
                  @endphp
                  @if(count($biometrics))
                      @foreach ($biometrics_dates as $bd)
                          <div class="col-lg-3 col-md-3 mb-3">
                              <h6 class="mt-0 mb-3 text-dark op-8 font-weight-bold">
                                  {{ date('M d, Y', strtotime($bd)) }}
                              </h6>
                              @foreach ($biometrics as $biometric)
                                  @if($bd == date('Y-m-d', strtotime($biometric->punch_time)))
                                      <ul class="list-timeline list-timeline-dark">
                                          <li class="list-timeline-item p-0 pb-3 pb-lg-4 d-flex flex-wrap flex-column {{ ($biometric->punch_state == 0) ? 'show' : '' }}">
                                              <p class="my-0 text-dark flex-fw text-sm"><span class="text-inverse op-8">{{ date('H:i', strtotime($biometric->punch_time)) }}</span> - {{ ($biometric->punch_state == 0) ? 'Punch In' : 'Punch Out' }}</p>
                                          </li>
                                      </ul>
                                  @endif
                              @endforeach
                          </div>
                      @endforeach
                  @else
                      <div class="col-lg-3 col-md-3 mb-3">
                          <h6 class="mt-0 mb-3 text-muted op-8 font-weight-bold">No record found.</h6>
                      </div>

                  @endif

              </div>
          </div>
      </div>
  </div>

  <hr>

</div>


<!-- /.container-fluid -->
@endsection
{{-- END CONTENT --}}
{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')

@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')

<script>

$(document).ready(function(){
     // Trigger click submit search button
   $('#btn-search-biometrics').click(function(){
      $('#btn-search-button').click();
   });
});

</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}