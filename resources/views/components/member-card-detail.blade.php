<div>
    <div class="card card-outline card-dark @if ($collapse) collapsed-card @endif" id="membersCard">
        <x-card-header title="{{ ($title=='' ? trans_choice('role.member',count($members)) : $title ) }}" icon="fas fa-user-tie"  :count="count($members)">
                @can('create-members')
                <a href="{{ route('membership.'.$entityType.'.create', ['language' => app()->getLocale(), $entityType => $entity])}}"
                    class="btn btn-success">
                    <i class="fas fa-plus-circle"></i> @lang( $entityType.'.member.action.create')
                </a>
                @endcan
        </x-card-header>
        <!-- /.card-header -->
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i>Oops</h5>
                    {{ session('error') }}
                </div>
            @endif
            @foreach ($members as $member)
                <ul class="list-group m-2">
                    <li class="list-group-item list-group-item-primary">
                        {{ $member->name }}
                    </li>
                    <li class="list-group-item ">
                        <i class="fas fa-envelope m-2"></i> {{ $member->email}} <i class="fas fa-phone m-2"></i> {{ $member->mobile}}
                    </li>
                    <li class="list-group-item ">
                        <i class="fas fa-address-card m-2"></i> {{ $member->address}}
                    </li>
                    <li class="list-group-item list-group-item-secondary">
                        <span data-toggle="tooltip" title="{{__('role.tooltip.delete',['name'=> $member->name])}}">
                            <button type="button" id="deleteMember" class="btn btn-danger btn-sm"
                                data-member-id="{{ $member->id }}" data-member-name="{{ $member->name }}"
                                data-toggle="modal" data-target="#modalDeleteMember"
                                @cannot('create-members') disabled @endcannot>{{__('Delete')}}
                            </button>
                        </span>
                        @can('update-members')
                        <span data-toggle="tooltip" title="{{__('role.tooltip.edit',['name'=> $member->name])}}">
                            <a type="button" href="{{ route('member.edit', ['language' => app()->getLocale(), 'member' => $member]) }}"
                                class="btn btn-primary btn-sm">{{__('Update')}}</a>
                        </span>
                        @endcan
                        @if ( (!$member->is_user) and (!$member->invitation()->exists()))
                            @can('update-members')
                            <span data-toggle="tooltip" title="{{__('role.tooltip.invite',['name'=> $member->name])}}">
                                <a href="{{ route('member.invite', ['member' => $member]) }}" type="button" id="inviteMember"
                                    class="btn btn-primary btn-sm " >{{__('role.send.invite')}}</a>
                            </span>
                            @endcan
                        @endif

                    </li>
                </ul>
            @endforeach
        </div>
        <!-- /.card-body -->
        <!-- /.card-footer -->
    </div>
</div>
