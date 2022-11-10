<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-outline-primary mr-2']) }}>
    {{ __('Cancel') }}
</button>

@push('js')
<script>
    $(document).ready(function(){
        $('#frmClose').click(function(e){
            history.back();
        });
    });
</script>
@endpush

