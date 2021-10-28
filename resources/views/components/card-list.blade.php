<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title font-weight-bold">{{ $cardTitle }}</h3>
        @if ( isset($cardNewAction))
        <div class="card-tools">
            <span>
                @can($cardNewAbility)
                <a href="{{ $cardNewAction }}" class="btn btn-success"><i class="fas fa-plus-circle pr-2"></i>{{ $cardNewTitle }}</a>
                @endcan
            </span>
        </div>
        @endif
        <!-- /.card-tools -->
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table width="100%" class="table table-hover table-bordered table-sm" id="table">
            <thead class="thead-light">
                {{ $extraHeaderSlot ?? ""}}
                <tr>
                    {{ $slot }}
                </tr>
            </thead>
                    {{ $tbodySlot ?? ""}}
        </table>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <div class="btn-toolbar justify-content-end" role="toolbar" aria-label="Toolbar with button groups">
            {{ $addButtons ?? "" }}
            <button type="button" class="btn btn-outline-primary mr-2" id="goBack" data-dismiss="modal">{{ __('Cancel')}}</button>
        </div>
    </div>
    <!-- /.card-footer -->
</div>
