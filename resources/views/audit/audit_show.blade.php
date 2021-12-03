@extends('layouts.page')

@section('content')
<x-card-list cardTitle="{{ __('audit.title.show') }}" >
<div id="audititem" class="container">
        <div class="row">
            <div class="col-md-3">
                <strong>@lang('audit.id')</strong>
            </div>
            <div class="col-md-9">{{ $audit->getMetadata()['audit_id'] }}</div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <strong>@lang('audit.event')</strong>
            </div>
            <div class="col-md-9">{{ $audit->getMetadata()['audit_event'] }}</div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <strong>@lang('audit.user')</strong>
            </div>
            <div class="col-md-9">{{ $audit->getMetadata()['user_name'] }}</div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <strong>@lang('audit.ip.address')</strong>
            </div>
            <div class="col-md-9">{{ $audit->getMetadata()['audit_ip_address'] }}</div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <strong>@lang('audit.user_agent')</strong>
            </div>
            <div class="col-md-9">{{ $audit->getMetadata()['audit_user_agent'] }}</div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <strong>@lang('audit.tags')</strong>
            </div>
            <div class="col-md-9">{{ $audit->getMetadata()['audit_tags'] }}</div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <strong>@lang('audit.url')</strong>
            </div>
            <div class="col-md-9">{{ $audit->getMetadata()['audit_url'] }}</div>
        </div>
    </div>

    <hr/>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>@lang('audit.attribute')</th>
                <th>@lang('audit.old_value')</th>
                <th>@lang('audit.new_value')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($audit->getModified() as $attribute => $modified)
                <tr">
                    <td><strong>{{ $attribute }}</strong></td>
                    <td class="text-danger">{{ $modified['old'] ?? '' }}</td>
                    <td class="text-success">{{ $modified['new'] ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</x-card-list>
@endsection

@section('js')

    <script>
        $(function() {
            $('#goBack').click(function(e){
                history.back();
            });
        });
    </script>

@endsection
