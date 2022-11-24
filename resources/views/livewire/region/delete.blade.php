<div>
    <!--Body-->
    <form  class="form-horizontal" id="deleteRegion_Form" >
        @csrf
        <div class="modal-body">
            <h4 class="text-left text-info">
                <p>{{$region->name}}</p>
            </h4>
            <div class="alert alert-danger" role="alert">
                {{ __('region.confirm.delete') }}
            </div>

        </div>
        <div class="modal-footer bg-light">
            <x-buttons.modal-back></x-buttons.modal-back>
            <x-buttons.primary wire:click="destroy({{$region}})" :disabled="false">{{ __('Submit') }}</x-buttons.primary>
        </div>
    </form>
</div>
