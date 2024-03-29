
<div class="modal fade right" id="{{ $modalId }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-side modal-bottom-right modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="col-12 modal-header text-center">
                <h3 class="modal-title" id="modalTitle">Titel</h3>
                <button type="button" class="close" id="frmClose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="card card-info">
                                <div class="alert alert-danger" role="alert"></div>
                                <div class="alert alert-success" role="alert">{{ __('Your data has been successfully saved')}}</div>
                    <form class="form-horizontal" id="{{ $modalFormId }}" action="" method="POST" enctype="multipart/form-data">
                        <div class="card-body">
                            @csrf
                            @yield('modal_content')
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <div class="btn-toolbar justify-content-end" role="toolbar"
                        aria-label="Toolbar with button groups">
                        <button type="button" class="btn btn-outline-primary mr-2" id="frmClose" data-dismiss="modal">{{ __('Cancel')}}</button>
                        <button type="button" class="btn btn-primary mr-2" id="frmSubmit">{{ __('Submit') }}</button>
                    </div>
                </div>

            </div>
        </div>
        <!--/.Content-->
    </div>
</div>

@push('js')

<script>
        $(document).ready(function(){
            @yield('modal_js')

            $('#{{ $modalId }}').on('show.bs.modal', function(e){
                $('.invalid-feedback').text("");
                $('.is-invalid').removeClass('is-invalid');
                $('.alert').hide();
            })

            $('#frmClose').click(function(e){
                location.reload();
            })

            $('#frmSubmit').click(function(e){
                e.preventDefault();
                $.ajax({
                    url: $('#{{ $modalFormId }}').attr('action'),
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: '{{ $modalFormMethod }}',
                        @yield('modal_js_data')
                    },
                    success: function(result){
                        @if ($stayOnSuccess)
                            $('.alert-danger').hide();
                            $('.alert-success').show()
                        @else
                            $('.alert-danger').hide();
                            $('#{{ $modalId }}').modal('hide');
                            location.reload();
                        @endif

                    },
                    error: function(response){
                        var result = response.responseJSON;
                        if(result.errors)
                        {
                           $('.alert-success').hide();
                           $('.alert-danger').html( '{{ __('Please fix the following errors') }}' );
                           $.each(result.errors, function(key, value){
                                $('.alert-danger').append('<li>'+value+'</li>');
                            });
                           $('.alert-danger').show();

                        }                         else
                        {
                            $('.alert').hide();
                            $('#{{ $modalId }}').modal('hide');
                        }

                    },
                });
            });
        });
        </script>

@endpush
