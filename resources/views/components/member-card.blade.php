<div>
    <div class="card card-outline card-dark collapsed-card" id="membersCard">
        <div class="card-header align-content-between">
            <h4 class="card-title pt-2"><i class="fas fa-user-tie fa-lg"></i> @lang('role.member') <span
                    class="badge badge-pill badge-primary">{{ count($members) }}</span></h4>
            <div class="card-tools">
                @can('create-members')
                <a href="{{ route('membership.'.$entityType.'.create', ['language' => app()->getLocale(), $entityType => $entity])}}"
                    class="btn btn-success">
                    <i class="fas fa-plus-circle"></i> @lang( $entityType.'.member.action.create')
                </a>
                @endcan
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                        class="fas fa-plus"></i>
                </button>
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @foreach ($members as $member)
                    <li class="list-group-item ">
                        <button type="button" id="deleteMember" class="btn btn-outline-danger btn-sm"
                            data-member-id="{{ $member->id }}" data-member-name="{{ $member->name }}"
                            data-toggle="modal"
                            data-target="#modalDeleteMember" @cannot('create-members') disabled @endcannot><i class="fa fa-trash"></i></button>
                        @can('update-members')
                        <a href="{{ route('member.edit', ['language' => app()->getLocale(), 'member' => $member]) }}"
                            class=" px-2">{{ $member->name }} <i class="fas fa-arrow-circle-right"></i></a>
                        @else
                        {{ $member->name }}
                        @endcan
                        @if (!$member->is_user)
                            @can('update-members')
                            <a href="{{ route('member.invite', ['member' => $member]) }}" type="button" id="inviteMember"
                                class="btn btn-outline-primary btn-sm " ><i class="far fa-paper-plane"></i> {{__('role.send.invite')}}</a>
                            @endcan
                        @endif
                        <button type="button" id="addMembership" class="btn btn-outline-primary btn-sm"
                            data-member-id="{{ $member->id }}" data-{{$entityType}}-id="{{ $entity->id }}"
                            data-toggle="modal" data-target="#modalMembershipAdd"  @cannot('update-members') disabled @endcannot><i class="fas fa-user-tag"></i></button>
                        @foreach ($member['memberships'] as $membership)
                            @if ($membership->membership_type == $entityClass and $membership->membership_id == $entity->id)
                                <button type="button" id="modMembership" class="btn btn-outline-primary btn-sm"
                                    data-membership-id="{{ $membership->id }}"
                                    data-function="{{ $membership->function }}"
                                    data-email="{{ $membership->email }}"
                                    data-role="{{ App\Enums\Role::getDescription($membership->role_id) }}"
                                    data-toggle="modal"
                                    data-target="#modalMembershipMod" @cannot('update-members') disabled @endcannot>{{ App\Enums\Role::getDescription($membership->role_id) }}</button>
                            @else
                                <span
                                    class="badge badge-secondary">{{ App\Enums\Role::getDescription($membership->role_id) }}</span>
                            @endif
                        @endforeach
                    </li>
                @endforeach
            </ul>
        </div>
        <!-- /.card-body -->
        <!-- /.card-footer -->
    </div>
</div>
