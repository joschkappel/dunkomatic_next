<div>
    <!-- card GYMS -->
    <div class="card card-outline card-dark collapsed-card" id="gymsCard">
        <x-cards.cardheader wire:model="gyms" title="{{trans_choice('gym.gym', 2)}}" icon="fas fa-building"  :count="count($gyms)" :showtools="true">
                @can('create-gyms')
                @if ( count($gyms) <= max(config('dunkomatic.allowed_gym_nos')))
                <a type="button" href="{{ route('club.gym.create', ['language' => app()->getLocale(), 'club'=> $club]) }}" class="btn btn-success btn-sm text-md">
                    <i class="fas fa-plus-circle"></i> @lang('gym.action.create')
                </a>
                @endif
                @endcan
        </x-cards.cardheader>
        <!-- /.card-header -->
        <div wire:model="gyms" class="card-body">
            <ul class="list-group list-group-flush">
            @foreach ($gyms as $gym)
                <li class="list-group-item ">
                    <span data-toggle="tooltip" title="{{__('gym.action.delete')}}">
                        <button wire:click='showDeleteModal({{$gym->id}})' type="button" id="deleteGym" class="btn btn-outline-danger btn-sm"
                            @cannot('create-gyms') disabled @else @if (($gym->games()->exists()) or ($club->teams->load('gym')->where('gym_id',$gym->id)->count() >0 )) disabled @endif @endcannot
                            ><i class="fa fa-trash"></i></button>
                    </span>
                    @can('update-gyms')
                        <span data-toggle="tooltip" title="{{__('gym.action.edit')}}">
                            <a href="{{ route('gym.edit', ['language' => app()->getLocale(), 'gym'=> $gym]) }}" class=" px-2">
                                {{ $gym->gym_no }} - {{ $gym->name }} <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </span>
                    @else
                        {{ $gym->gym_no }} - {{ $gym->name }}
                    @endcan
                    <span data-toggle="tooltip" title="{{__('gym.tooltip.map')}}">
                        <a href="{{ config('dunkomatic.maps_uri') }}{{ urlencode($gym->address) }}"
                            class=" px-4" target="_blank">
                            <i class="fas fa-map-marked-alt"></i>
                        </a>
                    </span>
                </li>
            @endforeach
            </ul>
        </div>
        <!-- /.card-body -->
        <!-- /.card-footer -->
    </div>
    <!-- /.card -->
    <div>
    <!-- all modals here -->
        <div class="modal fade" id="delete-gym-modal" tabindex="-1" role="dialog" aria-hidden="true"><livewire:club.gym.delete></div>
    <!-- all modals above -->
    </div>
</div>


@push('js')
    <script>
        window.addEventListener('closeDeleteModal', event => {
            $('#delete-gym-modal').modal('toggle');
        })
        window.addEventListener('openDeleteModal', event => {
            $('#delete-gym-modal').modal('show');
        })
    </script>
@endpush
