<div>
    <div class="modal-dialog modal-md" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header">
                <h3 class="col-12 modal-title text-center">{{ __('gym.title.delete') }}</h3>
                <button type="button" class="close" id="frmClose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <form class="form-horizontal" id="modalDeleteGym_Form">
                <div class="modal-body">
                    <h4 class="text-left text-dark">
                        <p>{{ trans_choice('gym.gym',1) }}
                            <span id="modalDeleteGym_Instance"></span>
                        </p>
                    </h4>

                    {{-- Gym No --}}
                    <div class="flex flex-col m-4">
                        <label class="form-label" for='gym_no'>@lang('gym.no')</label>
                        <input wire:model="gym_no" type="text" class="form-control" readonly id="gym_no">
                    </div>

                    {{-- Gym name --}}
                    <div class="flex flex-col m-4">
                        <label class="form-label" for='name'>@lang('gym.name')</label>
                        <input wire:model="name" type="text" class="form-control" readonly id="name">
                    </div> 

                    <div class="alert alert-danger" role="alert">
                        {{ __('gym.confirm.delete') }}
                    </div>

                </div>
                <div class="modal-footer bg-light">
                    <x-buttons.modal-back></x-buttons.modal-back>
                    <x-buttons.primary wire:click="destroy({{$gymId}})" :disabled="false">{{ __('Submit') }}</x-buttons.primary>
                </div>
            </form>

        </div>
        <!--/.Content-->
    </div>
</div>
