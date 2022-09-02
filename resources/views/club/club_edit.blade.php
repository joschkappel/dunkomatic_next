@extends('layouts.page')

@section('content')
<x-card-form cardTitle="{{ __('club.title.edit', ['club'=>$club->shortname]) }}" formAction="{{ route('club.update',['language'=>app()->getLocale(),'club' => $club]) }}" formMethod="PUT">
    <div class="form-group row">
        <label for="region" class="col-sm-4 col-form-label">@lang('club.region')</label>
        <div class="col-sm-6">
            <input type="text" readonly  value="{{ $club->region->code}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="club_no" class="col-sm-4 col-form-label">@lang('club.club_no')</label>
        <div class="col-sm-6">
            <input type="text" class="form-control @error('club_no') is-invalid @enderror" id="club_no" name="club_no" placeholder="@lang('club.club_no')" value="{{  (old('club_no')!='') ? old('club_no') : $club->club_no }}">
            @error('club_no')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <label for="shortname" class="col-sm-4 col-form-label">@lang('club.shortname')</label>
        <div class="col-sm-6">
            <input type="text" class="form-control @error('shortname') is-invalid @enderror" id="shortname" name="shortname" placeholder="@lang('club.shortname')" value="{{  (old('shortname')!='') ? old('shortname') : $club->shortname }}">
            @error('shortname')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row ">
        <label for="name" class="col-sm-4 col-form-label">@lang('club.name')</label>
        <div class="col-sm-6">
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="@lang('club.name')" value="{{  (old('name')!='') ? old('name') : $club->name }}">
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <label for="url" class="col-sm-4 col-form-label">URL</label>
        <div class="col-sm-6">
            <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" name="url" placeholder="URL" value="{{  (old('url')!='') ? old('url') : $club->url }}">
            @error('url')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group  row">
        <div class="col-md-4">
        </div>
        <div class="col-md-6">
            <div class="form-group  clearfix">
                <div class="icheck-info d-inline">
                    <input type="checkbox" id="inactive" name="inactive" @if ($club->inactive) checked @endif value="1">
                    <label for="inactive">@lang('Inactive') ?</label>
                </div>
            </div>
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
