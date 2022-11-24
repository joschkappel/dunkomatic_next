<div>
    @php
    $disabled = $errors->any() ||  empty($name) || empty($zip) || empty($city) || empty($street) ? true : false;
    @endphp

    <x-cards.form colWidth=6 :disabled="$disabled" cardTitle="{{__('gym.title.new', ['club' =>  $club->shortname]) }}" formAction="store">
        <div class="flex flex-col m-4">
        </div>

        {{-- Gym No --}}
        <div  class="flex flex-col m-4">
            <label class="form-label" for='gym_no'>@lang('gym.no')</label>
            <input wire:model.defer="gym_no" type="text" readonly class="form-control @error('gym_no') is-invalid  @enderror" id="gym_no"">
            @error('gym_no')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Gym name --}}
        <div class="flex flex-col m-4">
            <input wire:model.debounce.500ms="name" type="text" class="form-control @error('name') is-invalid @else @if ($name != null ) is-valid @endif @enderror" id="name" placeholder="@lang('gym.name')">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Gym zipcode --}}
        <div class="flex flex-col m-4">
            <input wire:model.debounce.500ms="zip" type="text" class="form-control @error('zip') is-invalid @else @if ($zip != null ) is-valid @endif @enderror" id="zip" placeholder="@lang('role.zipcode')">
            @error('zip')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Gym city --}}
        <div class="flex flex-col m-4">
            <input wire:model.defer="city" type="text" class="form-control @error('city') is-invalid @else @if ($city != null ) is-valid @endif @enderror" id="city" placeholder="@lang('role.city')">
            @error('city')
                <div wire:model.debounce.500ms="city" class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Gym street --}}
        <div class="flex flex-col m-4">
            <input wire:model.debounce.500ms="street" type="text" class="form-control @error('street') is-invalid @else @if ($street != null ) is-valid @endif @enderror" id="street" placeholder="@lang('role.street')">
            @error('street')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="flex flex-col m-4">
            <button type="button" id="adrval" class="btn btn-secondary mr-2" @if ($disabled) disabled @endif>{{ __('gym.action.validate_adr')}}</button>
        </div>

    </x-cards.form>
</div>

@push('js')
<script>
    $(function() {

        $("button#adrval").click( function(){
            let street =  @this.street;
            let zip = @this.zip;
            let city = @this.city;

            var uri = "{{ config('dunkomatic.maps_uri') }}"+street+", "+zip+" "+city;
            var res = encodeURI(uri);
            window.open(res, "_blank");
        });

    });


</script>
@endpush
