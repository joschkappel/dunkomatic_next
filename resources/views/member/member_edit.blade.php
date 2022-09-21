@extends('layouts.page')

@section('content')
<div class="row">
    <div class="col-sm">
        <x-card-form colWidth=12 cardTitle="{{ __('role.title.edit', ['member'=>$member->name]) }}" formAction="{{ route('member.update',['member' => $member]) }}" formMethod="PUT">
                    <input type="hidden" id="backto" name="backto" value={{$backto}}>
                    @if ($member->is_user)
                    <div class="form-group row">
                        <div class="col-sm">
                            <div class="alert alert-info" role="alert">{{__('role.hasuser')}}</div>
                        </div>
                    </div>
                    @endif
                    <div class="form-group row">
                        <div class="col-sm">
                            <input type="text" class="form-control @error('firstname') is-invalid @enderror"
                                id="firstname" name="firstname" placeholder="@lang('role.firstname')" value="{{ old('firstname') ? old('firstname') : $member->firstname }}"></input>
                            @error('firstname')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm">
                            <input type="text" class="form-control @error('lastname') is-invalid @enderror"
                                id="lastname" name="lastname" placeholder="@lang('role.lastname')" value="{{ old('lastname') ? old('lastname') : $member->lastname }}"></input>
                            @error('lastname')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm">
                            <input type="text" class="form-control @error('street') is-invalid @enderror"
                                id="street" name="street" placeholder="@lang('role.street')" value="{{ old('street') ? old('street') : $member->street }}"></input>
                            @error('street')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm">
                            <input type="text" class="form-control @error('zipcode') is-invalid @enderror"
                                id="zipcode" name="zipcode" placeholder="@lang('role.zipcode')" value="{{ old('zipcode') ? old('zipcode') : $member->zipcode }}"></input>
                            @error('zipcode')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm">
                            <input type="text" class="form-control @error('city') is-invalid @enderror"
                                id="city" name="city" placeholder="@lang('role.city')" value="{{old('city') ? old('city') : $member->city }}"></input>
                            @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm">
                            <input type="text" class="form-control @error('mobile') is-invalid @enderror"
                                id="mobile" name="mobile" placeholder="@lang('role.mobile')" value="{{ old('mobile') ? old('mobile') : $member->mobile }}"></input>
                            @error('mobile')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm">
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                id="phone" name="phone" placeholder="@lang('role.phone')" value="{{ old('phone') ? old('phone') : $member->phone }}"></input>
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm">
                            <input type="text" class="form-control @error('email1') is-invalid @enderror"
                                id="email1" name="email1" placeholder="@lang('role.email1')" value="{{ old('email1') ? old('email1') : $member->email1 }}"></input>
                            @error('email1')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm">
                            <input type="text" class="form-control @error('email2') is-invalid @enderror"
                                id="email2" name="email2" placeholder="@lang('role.email2')" value="{{ old('email2') ? old('email2') : $member->email2 }}"></input>
                            @error('email2')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm">
                            <input type="text" class="form-control @error('fax') is-invalid @enderror"
                                id="fax" name="fax" placeholder="@lang('role.fax')" value="{{ old('fax') ? old('fax') : $member->fax }}"></input>
                            @error('fax')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
        </x-card-form>
    </div>
    <div class="col-sm">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <div class="card card-outline card-primary ">
                        <div class="card-header">
                            <div class="card-tools">
                                <span>
                                    @can('create-memberships')
                                    <button type="button" id="addMembership" class="btn btn-success btn-sm"
                                        data-member-id="{{ $member->id }}" data-{{$entity_type}}-id="{{ $entity_id }}"
                                        data-toggle="modal" data-target="#modalMembershipAdd"  @cannot('update-members') disabled @endcannot>{{__('role.action.create')}}</button>
                                    @endcan
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @foreach ($member->memberships as $ms)
            @if ( $memberships->contains($ms))
                <x-card-form :omitCancel="true" colWidth=12 cardTitle="{{ __('role.title.role.edit', ['member'=>$member->name,'role'=> $ms->description]) }}" formAction="{{ route('membership.update',['membership' => $ms]) }}" formMethod="PUT">
                    <div class="form-group row">
                        <div class="col-sm-8">
                            <input type="text"
                                class="form-control @error('email', 'err_member') is-invalid @enderror"
                                id="modmememail" name="email" placeholder="@lang('role.role.email')"
                                value="{{ old('email') ?? $ms->email }}"></input>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-8">
                            <input type="text"
                                class="form-control @error('function') is-invalid @enderror"
                                id="modmemfunction" name="function" placeholder="@lang('role.function')"
                                value="{{  old('function') ?? $ms->function }}">
                            @error('function')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <x-slot:addButtons>
                        <button type="button" class="btn btn-danger mr-2" onclick="deleteMembership({{$ms->id}})">{{ __('Delete')}}</button>
                    </x-slot:addButtons>
                </x-card-form>
            @endif
        @endforeach
        @foreach ($member->memberships as $ms)
            @if ( ! $memberships->contains($ms))
                <x-card-form :omitCancel="true"  :omitSubmit="true" colWidth=12 cardTitle="{{ __('role.title.role.show', ['member'=>$member->name,'role'=> $ms->description]) }}" formAction="">
                    <div class="form-group row">
                        <div class="col-sm-8">
                            <input type="text" class="form-control" readonly value="{{ old('email') ?? $ms->email }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-8">
                            <input type="text" class="form-control" readonly  value="{{  old('function') ?? $ms->function }}">
                        </div>
                    </div>
                </x-card-form>
            @endif
        @endforeach
    </div>
</div>
@include('member/includes/membership_add')
@endsection

@section('js')
<script>
        $(document).ready(function(){
            $('#frmClose').click(function(e){
                history.back();
            })
        });
        $(function() {
            $("#addMembership").click(function() {
                var url = "{{ $add_url }}";
                $('#modalAddMembership_Form').attr('action', url);
                $('#modalAddMembership').modal('show');
            });
        });
        function deleteMembership(msid) {
            var url = "{{ route('membership.destroy', ['membership' => ':membershipid:']) }}";
            url = url.replace(':membershipid:', msid);
            $.ajax({
                url: url,
                type: 'DELETE',
                dataType: "json",
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: 'DELETE'
                },
                success: function(response) {
                    location.reload();
                },
            });
        };

</script>
@endsection
