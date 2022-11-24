<div>
    @php
    $disabled = $errors->any() || empty($shortname)  || empty($name) || empty($club_no) ? true : false;
    @endphp

    <x-cards.form  colWidth=6 :disabled="$disabled" cardTitle="{{ __('club.title.new', ['region' =>$club->region->name ]) }}" formAction="update">
        {{-- Club No --}}
        <div class="flex flex-col m-4">
            <input wire:model.debounce.500ms="club_no" type="text" class="form-control @error('club_no') is-invalid @else @if ($club_no != $club->club_no ) is-valid @endif @enderror"
                id="club_no" placeholder="@lang('club.club_no')">
            @error('club_no')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Shortname --}}
        <div class="flex flex-col m-4">
            <input wire:model.debounce.500ms="shortname" type="text" class="form-control @error('shortname') is-invalid @else @if ($shortname != $club->shortname ) is-valid @endif @enderror"
                id="shortname" placeholder="@lang('club.shortname')">
            @error('shortname')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Name --}}
        <div class="flex flex-col m-4">
            <input wire:model.debounce.500ms="name" type="text" class="form-control @error('name') is-invalid @else @if ($name != $club->name ) is-valid @endif @enderror " id="name"
                placeholder="@lang('club.name')">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Url --}}
        <div class="flex flex-col m-4">
            <input wire:model.debounce.500ms="url" type="text" class="form-control @error('url') is-invalid @else @if ($url != $club->url ) is-valid @endif @enderror" id="url"
                placeholder="URL">
            @error('url')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="flex flex-col m-4">
            <div class="form-group  clearfix">
                <div class="icheck-info d-inline">
                    <input wire:model="inactive" type="checkbox" id="inactive">
                    <label for="inactive">@lang('Inactive')</label>
                    @error('inactive')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                </div>
            </div>
        </div>
    </x-cards.form>
</div>