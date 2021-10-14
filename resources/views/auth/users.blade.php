@extends('layouts.page')

@section('content')
<x-card-list cardTitle="{{ __('auth.title.approve') }}">
    <th>{{ __('auth.user') }}</th>
    <th>{{__('auth.email')}}</th>
    <th>{{ __('auth.registered_at') }}</th>
    <th>{{__('Action')}}</th>
    <x-slot name="tbodySlot">
        <tbody>
        @forelse ($users as $auser)
            <tr>
                <td>{{ $auser->name }}</td>
                <td>{{ $auser->email }}</td>
                <td>{{ $auser->created_at }}</td>
                <td><a href="{{ route('admin.user.edit', ['language' => app()->getLocale(), 'user' => $auser->id ]) }}" id="btnApprove"
                        class="btn btn-primary btn-sm">{{ __('auth.action.approve') }}</a></td>
            </tr>
        @empty
            <tr>
                <td colspan="4">{{__('auth.no_users_found')}}</td>
            </tr>
        @endforelse
        </tbody>
    </x-slot>
</x-card-list>
@endsection
