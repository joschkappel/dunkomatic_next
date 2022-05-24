<div class="modal fade right" id="modalSelectMember" tabindex="-1" role="dialog" aria-labelledby="selectMemberModal" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-md" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-secondary">
                 <h5 class="modal-title">@lang('role.member.title.select')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-sm-8">
                        <div class="input-group mb-3">
                            <select class="js-sel-member js-states form-control select2" name="selMember" id='selMember'></select>
                        </div>
                    </div>
                 </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('OK')</button>
            </div>
            <!--/.Content-->
        </div>
    </div>
    <!--Modal: modalRelatedContent-->
</div>
