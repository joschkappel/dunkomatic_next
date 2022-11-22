<div>
    @php
    $disabled = $errors->any() || empty($gym_no)  || empty($name) || empty($zip) || empty($city) || empty($street) ? true : false;
    @endphp

    <div class="modal-dialog modal-md">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header">
                <h3 class="col-12 modal-title text-center">{{ __('gym.title.new', ['club' =>  $club->shortname]) }}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" id="modalDeleteGym_Form">
                <div class="modal-body">
                    <div class="flex flex-col m-4">
                    </div>

                    {{-- Gym No --}}
                    <div wire:init="loadGyms" class="flex flex-col m-4">
                        <label class="form-label" for='gym_no'>@lang('gym.no')</label>
                        <div>
                            <select  class="form-control select2" id='gym_no'>
                            </select>
                        </div>
                        @error('gym_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Gym name --}}
                    <div class="flex flex-col m-4">
                        <label for="title" class="form-label">@lang('gym.name')</label>
                        <input wire:model.defer="name" type="text" class="form-control @error('name') is-invalid @else @if ($name != null ) is-valid @endif @enderror" id="name" placeholder="@lang('gym.name')">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Gym zipcode --}}
                    <div class="flex flex-col m-4">
                        <label for="title" class="form-label">@lang('role.zipcode')</label>
                        <input wire:model.defer="zip" type="text" class="form-control @error('zip') is-invalid @else @if ($zip != null ) is-valid @endif @enderror" id="zip" placeholder="@lang('role.zipcode')">
                        @error('zip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Gym city --}}
                    <div class="flex flex-col m-4">
                        <label for="title" class="form-label">@lang('role.city')</label>
                        <input wire:model.defer="city" type="text" class="form-control @error('city') is-invalid @else @if ($city != null ) is-valid @endif @enderror" id="city" placeholder="@lang('role.city')">
                        @error('city')
                            <div wire:model.debounce.500ms="city" class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Gym street --}}
                    <div class="flex flex-col m-4">
                        <label for="title" class="form-label">@lang('role.street')</label>
                        <input wire:model.defer="street" type="text" class="form-control @error('street') is-invalid @else @if ($street != null ) is-valid @endif @enderror" id="street" placeholder="@lang('role.street')">
                        @error('street')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer bg-light">
                        <x-buttons.modal-back></x-buttons.modal-back>
                        <button type="button" id="adrval" class="btn btn-secondary mr-2">{{ __('gym.action.validate_adr')}}</button>
                        <x-buttons.primary wire:click="store" :disabled="false">{{ __('Submit') }}</x-buttons.primary>
                </div>
            </form>
        </div>
    </div>

</div>


@push('js')
<script>
    $(document).ready(function() {
        Livewire.on('loadGymNos', gymList => {
            $("#gym_no").select2({
                placeholder: "@lang('gym.no')...",
                width: '100%',
                multiple: false,
                allowClear: false,
            });
            $('#gym_no').val(null).trigger('change');
            for ( g in gymList ){
                var newOption = new Option( g, g, false, false);
                $('#gym_no').append(newOption).trigger('change');
            };
        });
        $("button#adrval").click( function(){
            let street =  @this.street;
            let zip = @this.zip;
            let city = @this.city;

            var uri = "{{ config('dunkomatic.maps_uri') }}"+street+", "+zip+" "+city;
            var res = encodeURI(uri);
            window.open(res, "_blank");
        });

        $("#gym_no").select2({
            placeholder: "@lang('gym.no')...",
            width: '100%',
            multiple: false,
            allowClear: false,
        });


        $('#gym_no').on('change', function (e) {
            var data = $('#gym_no').select2('val');
            @this.set('gym_no', data);
        });
    });
</script>
@endpush
