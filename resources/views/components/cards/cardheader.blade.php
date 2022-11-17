<div>
    <div class="card-header text-md">
        <i class="{{ $icon }} fa-lg"></i>
        {{ $title }}
        <div class="card-tools">
            {{ $slot }}
            <livewire:components.counter :count='$count'/>
            @if ($showtools)
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
            @endif
        </div>
        <!-- /.card-tools -->
    </div>
</div>
