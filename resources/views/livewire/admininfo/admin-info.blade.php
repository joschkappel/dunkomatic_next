<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    <x-card-list cardTitle="User Statistics" >
        <livewire:admininfo.users-by-providers/>
        <x-slot:addButtons>
            <button class="btn btn-primary mr-2" wire:click="$emit('onSliceClickClear')">Reset</button>
        </x-slot:addButtons>
    </x-card-list>
    <x-card-list cardTitle="Health Statistics" >
        <livewire:admininfo.healthevents-by-type/>
        <x-slot:addButtons>
            <button class="btn btn-primary mr-2" wire:click="$emit('onSliceClickClear')">Reset</button>
        </x-slot:addButtons>
    </x-card-list>
    <x-card-list cardTitle="Audit Statistics" >
        <livewire:admininfo.audits-by-model/>
    </x-card-list>
    <x-card-list cardTitle="Download Statistics" >
        <livewire:admininfo.downloads-by-report/>
    </x-card-list>
</div>






