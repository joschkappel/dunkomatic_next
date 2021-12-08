@extends('layouts.page')

@section('content')
<div class="container-fluid ">
    <div class="row">
        <div class="col-md-6 pd-2">
            <x-card-form colWidth=12 cardTitle="{{ __('auth.title.edit') }}" formAction="{{ route('admin.user.update', ['user' => Auth::user()]) }}" formMethod="PUT" >
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-4 col-form-label">@lang('auth.full_name')</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ Auth::user()->name }}">
                                            @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="email" class="col-sm-4 col-form-label">@lang('auth.email')</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ Auth::user()->email }}">
                                            @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="selLocale" class="col-sm-4 col-form-label">{{__('auth.user.preferred.language')}}</label>
                                        <div class="col-sm-4">
                                        <div class="input-group mb-3">
                                            <select class='js-sel-locale form-control select2' id='selLocale' name="locale">
                                                <option @if (Auth::user()->locale == 'en') selected @endif value="en">{{__('english')}}</option>
                                                <option @if (Auth::user()->locale == 'de') selected @endif value="de">{{__('deutsch')}}</option>
                                            </select>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="lastlogin" class="col-sm-4 col-form-label">@lang('auth.lastlogin_at')</label>
                                        <div class="col-sm-6">
                                            <input type="text" readonly class="form-control" id="lastlogin" value="{{ Carbon\Carbon::parse(Auth::user()->lastLoginAt())->diffForHumans( Carbon\Carbon::now() )}}">
                                        </div>
                                    </div>
            </x-card-form>
            @include('member.includes.member_edit')
        </div>
        <div class="col-md-6 pd-2">
            <x-card-list cardTitle="{{ __('audit.audittrail') }}" >
                @php
                    $adate = null;
                @endphp
                <div class="timeline">
                    <!-- Timeline time label -->
                    @if($audits->count() == 0)
                        <div class="time-label">
                            <span class="bg-info">{{ __('audit.list.empty') }}</span>
                        </div>
                    @endif
                    @foreach ( $audits as $a )
                        @if ($a->created_at->isoFormat('ll')  != $adate)
                            <div class="time-label">
                                <span class="bg-green">{{ \Carbon\CarbonImmutable::parse($a->created_at)->locale( app()->getLocale() )->isoFormat('ll') }}</span>
                            </div>
                            @php
                                $adate = $a->created_at->isoFormat('ll') ;
                            @endphp
                        @endif
                        <div>
                        <!-- Before each timeline item corresponds to one icon on the left scale -->
                            <i class="fas fa-camera-retro bg-blue"></i>
                            <!-- Timeline item -->
                            <div class="timeline-item">
                                <!-- Time -->
                                <span class="time"><i class="fas fa-clock"></i> {{ \Carbon\CarbonImmutable::parse($a->created_at)->locale( app()->getLocale() )->isoFormat('HH:mm:ss') }}</span>
                                <!-- Header. Optional -->
                                @if ($a->event != 'deleted')
                                    @if ($a->auditable_type == 'App\Models\Club') @php $tname = App\Models\Club::find($a->auditable_id)->shortname  ?? __('audit.unknown.instance') @endphp
                                    @elseif ($a->auditable_type == 'App\Models\League') @php $tname = App\Models\League::find($a->auditable_id)->shortname ?? __('audit.unknown.instance') @endphp
                                    @elseif ($a->auditable_type == 'App\Models\Team') @php $tname = App\Models\Team::find($a->auditable_id)->name ?? __('audit.unknown.instance') @endphp
                                    @else @php $tname = __('audit.unknown.type') @endphp
                                    @endif
                                @else
                                    @php $tname = $a->old_values['shortname'] ?? __('audit.unknown.instance') @endphp
                                @endif
                                <h3 class="timeline-header"><a href="#">{{ App\Models\User::find($a->user_id)->name}}</a> {!! __('audit.'.$a->event, ['type'=> __('audit.'.$a->auditable_type), 'typename'=>'<span class="text-primary inline">'.$tname.'</span>' ]) !!} </h3>
                            </div>
                        </div>
                    @endforeach
                    <!-- The last icon means the story is complete -->
                    <div>
                        <i class="fas fa-clock bg-gray"></i>
                    </div>
                </div>
            </x-card-list>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(function() {
        $('#frmClose').click(function(e){
            history.back();
        });
        $('#goBack').click(function(e){
            history.back();
        });
        $("#updateMember").collapse("toggle");

        function formatLocale (locale) {

            var country = locale.id;
            if (country == "en"){
                country = 'gb';
            }
            var $locale = $(
                '<span class="flag-icon flag-icon-'+country+'"></span><span> '+locale.text+'</span></span>'
            );

            // Use .text() instead of HTML string concatenation to avoid script injection issues
            //$locale.find("span").text(locale.text);

            return $locale;
        };

        $("#selLocale").select2({
                width: '100%',
                multiple: false,
                allowClear: false,
                minimumResultsForSearch: Infinity,
                templateSelection: formatLocale
            });
    });
</script>
@stop
