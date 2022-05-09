<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title font-weight-bold pt-2"><i class="fas fa-link text-primary mx-2"></i>Quicklinks</h4>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        @foreach ( $links as $l )
            <a href="{{ $l['url']}}" class="card-link">{{ $l['text'] }}</a>
        @endforeach
    </div>
</div>
