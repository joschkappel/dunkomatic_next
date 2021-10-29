@extends('layouts.page')

@section('content')
<x-card-form cardTitle="{{ __('club.title.new', ['region' =>$region->name ]) }}" formAction="{{ route('club.store') }}" >
                            <div class="form-group row">
                                <label for="region" class="col-sm-4 col-form-label">@lang('club.region')</label>
                                <div class="col-sm-6">
                                    <input type="text" readonly class="form-control @error('region') is-invalid @enderror"
                                        id="region" name="region" value="{{ $region->name }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="club_no" class="col-sm-4 col-form-label">@lang('club.club_no')</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control @error('club_no') is-invalid @enderror"
                                        id="club_no" name="club_no" placeholder="@lang('club.club_no')" value="0614...">
                                    @error('club_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="shortname" class="col-sm-4 col-form-label">@lang('club.shortname')</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control @error('shortname') is-invalid @enderror"
                                        id="shortname" name="shortname" placeholder="@lang('club.shortname')"
                                        value="{{ old('shortname') }}">
                                    @error('shortname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label">Name</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                        name="name" placeholder="@lang('club.name')" value="{{ old('name') }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="url">URL</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control @error('url') is-invalid @enderror" id="url"
                                        name="url" placeholder="URL" value="{{ old('url') }}">
                                    @error('url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
</x-card-form>
@endsection

@section('js')
    <script>
        $(document).ready(function(){
            $('#frmClose').click(function(e){
                history.back();
            })
        });
    </script>
@endsection
