<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-{{ $colWidth }}">
            <!-- general form elements -->
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">{{ $cardTitle }}</h3>
                </div>
                <!-- /.card-header -->
                <form id="cardForm" class="form-horizontal" action="{{ $formAction }}" method="POST" @if ($isMultipart) enctype="multipart/form-data"  @endif>
                    <div class="card-body">
                        @csrf
                        @method( $formMethod )
                        @if ($errors->any())
                        {{-- {{ dd($errors) }} --}}
                        <div class="alert alert-danger" role="alert">
                            @lang('Please fix the following errors')
                        </div>
                        @endif

                        {{ $slot }}

                    </div>
                    <div class="card-footer">
                        <div class="btn-toolbar justify-content-end" role="toolbar" aria-label="Toolbar with button groups">
                            @if ( ! $omitCancel  )
                            <button type="button" class="btn btn-outline-primary mr-2" dusk="frmClose" id="frmClose">{{ __('Cancel')}}</button>
                            @endif
                            {{ $addButtons ?? "" }}
                            @if( ! $omitSubmit )
                            <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
