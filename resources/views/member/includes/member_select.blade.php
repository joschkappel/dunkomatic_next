<div class="modal fade right" id="modalSelectMember" tabindex="-1" role="dialog" aria-labelledby="selectMemberModal" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-side modal-bottom-right modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-info">
            <p class="heading" id="dheader">@lang('role.member.title.select')</p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="card card-info">
                        <div class="card-body">
                          <div class="form-group row">
                            <div class="col-sm-8">
                              <select class="js-sel-member js-states form-control select2" name="selMember" id='selMember'></select>
                            </div>
                          </div>
                        </div>
                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('OK')</button>
                            </div>
                        </div>
                </div>
            </div>
            <!--/.Content-->
        </div>
    </div>
    <!--Modal: modalRelatedContent-->
</div>
