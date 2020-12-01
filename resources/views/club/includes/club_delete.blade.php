<div class="modal fade right" id="modalDeleteClub" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-side modal-bottom-right modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-danger">
                <p class="heading" id="dheader">@lang('club.title.delete')
                <span id="club_shortname"></span>
                </p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="card card-info">

                    <form id="confirmDeleteClub" class="form-horizontal" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="card-body">
                          <h4 class="text-left text-danger"><span>{{ $club->name }}</span> </h4>
                          <p class="text-left">@lang('club.confirm.delete',['club'=>$club->shortname,'noteam'=>count($teams),'nomember'=>count($members),'nogym'=>count($gyms)])</p>
                        </div>
                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                <button type="submit" class="btn btn-danger">{{__('Submit')}}</button>
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
