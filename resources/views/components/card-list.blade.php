<div class="card card-outline card-primary ">
    <div class="card-header">
        <h3 class="card-title font-weight-bold" id="title{{$tableId}}">{{ $cardTitle }}</h3>
        @if ( isset($cardNewAction))
        <div class="card-tools">
            <span>
                @can($cardNewAbility)
                <a href="{{ $cardNewAction }}" class="btn btn-success"><i class="fas fa-plus-circle pr-2"></i>{{ $cardNewTitle }}</a>
                @endcan
            </span>
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
        </div>
        @else
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
        </div>
        @endif
        <!-- /.card-tools -->
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="table-responsive">
        <table width="100%" class="table table-sm {{ $tableClass }}" id="{{ $tableId ?? 'table' }}">
            <thead class="thead-light">
                {{ $extraHeaderSlot ?? ""}}
                <tr>
                    {{ $slot }}
                </tr>
            </thead>
                    {{ $tbodySlot ?? ""}}
        </table>
        </div>
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
