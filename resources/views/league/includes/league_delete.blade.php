<div class="modal fade right" id="modalDeleteLeague" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-side modal-bottom-right modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-danger">
                <p class="heading" id="dheader">@lang('league.title.delete')
                <span id="league_shortname"></span>
                </p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="card card-info">

                    <form id="confirmDeleteLeague" class="form-horizontal" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="card-body">
                          <h4 class="text-left text-danger"><span>{{ $league->name }}</span> </h4>
                          <p class="text-left">@lang('league.confirm.delete',['league'=>$league->shortname,'noteam'=>count($assigned_teams),'nomember'=>count($members)])</p>
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
