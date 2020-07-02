<div class="modal fade right" id="modalCreateClub" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-side modal-bottom-right modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-info">
                <p class="heading">new club for region {{ Auth::user()->region }}</p>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">

                <!-- general form elements -->
                <div class="card card-info">

                    <form class="form-horizontal" action="{{ route('club.store') }}" method="post">
                        <div class="card-body">
                            @csrf
                            @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                Please fix the following errors
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="title" class="col-sm-2 col-form-label">region</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('region') is-invalid @enderror" id="region" name="region" placeholder="region" value="HBVDA">
                                    @error('region')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="title" class="col-sm-2 col-form-label">club_no</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('club_no') is-invalid @enderror" id="club_no" name="club_no" placeholder="club_no" value="1233">
                                    @error('club_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="title" class="col-sm-2 col-form-label">shortname</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('shortname') is-invalid @enderror" id="shortname" name="shortname" placeholder="Shprtname" value="{{ old('shortname') }}">
                                    @error('shortname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="title" class="col-sm-2 col-form-label">name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Name" value="{{ old('name') }}">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group" class="col-sm-2 col-form-label">
                                <label for="url">Url</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" name="url" placeholder="URL" value="{{ old('url') }}">
                                    @error('url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
