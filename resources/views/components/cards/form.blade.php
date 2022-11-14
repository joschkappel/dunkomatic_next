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
                <form wire:submit.prevent='{{$formAction}}' id="cardForm" class="form-horizontal flex flex-col space-y-4" @if ($isMultipart) enctype="multipart/form-data"  @endif>
                    <div class="card-body flex flex-col space-y-4">
                        @csrf
                        @if ($errors->any())
                        {{-- {{ dd($errors) }} --}}
                        <div class="alert alert-danger" role="alert">
                            @lang('Please fix the following errors')
                        </div>
                        @endif
                        @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            @lang( session('success'))
                        </div>
                        @endif
                        @if ($cardChangeNote != '')
                            <div class="form-group row ">
                                <div class="col-sm-4 text-xs text-info text-nowrap">{{ $cardChangeNote }}</div>
                            </div>
                        @endif

                        {{ $slot }}

                    </div>
                    <div class="card-footer">
                        <div class="btn-toolbar justify-content-end" role="toolbar" aria-label="Toolbar with button groups">
                            @if ( ! $omitCancel  )
                                {{-- Back Button --}}
                                <x-buttons.back  dusk="frmClose" id="frmClose">
                                </x-buttons.back>
                            @endif
                            {{ $addButtons ?? "" }}
                            @if( ! $omitSubmit )
                                {{-- Submit Button --}}
                                <x-buttons.primary wire:target='{{$formAction}}' :disabled="$disabled" form="cardForm">
                                    {{__('Submit')}}
                                </x-buttons.primary>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
