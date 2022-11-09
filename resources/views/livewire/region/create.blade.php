<div class="flex flex-col space-y-4">
    @php
    $disabled = $errors->any() || empty($code) || empty($name) ? true : false;
    @endphp

    <x-cards.form colWidth=6 :disabled="$disabled" cardTitle="{{ __('region.title.create') }}" formAction="store">
        {{-- Code --}}
        <div class="flex flex-col m-4">
            <input  wire:model.debounce.500ms="code" type="text"  class="form-control @error('code') border-red-400 border-2 @enderror" id="code" name="code" placeholder="{{__('region.code')}}">
            @error('code') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        {{-- Name --}}
        <div class="flex flex-col m-4">
            <input wire:model.debounce.500ms="name" type="text" class="form-control @error('name') border-red-400 border-2 @enderror" id="name" name="name" placeholder="{{__('region.name')}}">
            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        {{-- SB Hq --}}
        <div wire:ignore class="flex flex-col m-4">
            <select class='form-control select2 @error('hq') border-red-400 border-2 @enderror' id='selRegion' name='hq'>
                @foreach ($regions as $r )
                <option value="{{$r->code}}">{{$r->name}}</option>
                @endforeach
            </select>
            @error('hq') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

    </x-cards.form>
</div>

@push('js')

  <script>
        $(document).ready(function(){
            $('#frmClose').click(function(e){
                history.back();
            });

            $("#selRegion").select2({
                multiple: false,
                width: '100%',
                allowClear: true,
                placeholder: "{{__('region.hq').' '.__('club.region')}}",
            });
            $('#selRegion').on('change', function (e) {
                var data = $('#selRegion').select2("val");
                @this.set('hq', data);
            });
        });
 </script>

@endpush
