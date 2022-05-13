<div>
    <div class="card-header text-md">
        <i class="{{ $icon }} fa-lg"></i>
        {{ $title }}
        <div class="card-tools">
            {{ $slot }}
            @if ($count!=null)
                <span class="badge badge-primary text-md ">{{ $count }}</span>
            @endif
            @if ($showtools)
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
            @endif
        </div>
        <!-- /.card-tools -->
    </div>
</div>
