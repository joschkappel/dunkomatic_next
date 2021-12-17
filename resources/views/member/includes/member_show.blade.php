<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">{{ __('role.title.show', ['member'=> $member->name ]) }}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
            <div class="form-group row">
                <div class="col-sm-6">
                    <p class="text-left">{{ $member->name }}</p>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6">
                    <p class="text-left">{{ $member->street }}</p>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6">
                    <p class="text-left">{{ $member->zipcode }}</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-left">{{ $member->city }}</p>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6">
                    <p class="text-left">{{ $member->mobile }}</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-left">{{ $member->phone }}</p>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6">
                    <p class="text-left">{{ $member->email1 }}</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-left">{{ $member->email2 }}</p>
                </div>
            </div>
            <div class="form-group row">
                <p class="text-left">{{ $member->fax }}</p>
            </div>
        </div>
</div>
</div>
</div>
</div>
