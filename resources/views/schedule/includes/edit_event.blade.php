<div class="modal fade right" id="modalEditEvent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-side modal-bottom-right modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-info">
                <p class="heading">@lang('schedule.title.event.edit')</p>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="card card-info">

                    <form class="form-horizontal" id="editEventForm" action="" method="POST" data-remote="true">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                          @if ($errors->any())
                          <div class="alert alert-danger" role="alert">
                              Please fix the following errors
                          </div>
                          @endif
                          <div class="form-group row">
                              <label for="game_day" class="col-sm-2 col-form-label">Game Day</label>
                              <div class="col-sm-10">
                                  <input type="text" readonly class="form-control" id="game_day" value="">
                              </div>
                          </div>
                          <div class="form-group row">
                              <label for="game_date" class="col-sm-2 col-form-label">Game Date</label>
                              <div class="col-sm-10">
                                  <div class="input-group date" id="game_date" data-target-input="nearest">
                                      <input type="text" name='game_date' id='game_date' class="form-control datetimepicker-input " data-target="#game_date" />
                                      <div class="input-group-append" data-target="#game_date" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                        <div class="form-group row ">
                            <label for="full_weekend" class="col-sm-2 col-form-label">Full Weekend ?</label>
                            <div class="col-sm-10">
                              {{ Form::hidden('full_weekend', 0) }}
                              {{ Form::checkbox('full_weekend', '1') }}
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                <button type="submit" class="btn btn-info">Submit</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <!--/.Content-->
        </div>
    </div>
    <!--Modal: modalRelatedContent-->
</div>
