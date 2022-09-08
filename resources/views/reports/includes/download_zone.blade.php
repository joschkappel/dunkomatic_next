<x-modal-help modalId="modalDownloadZone" modalTitle="Download Zone" modalSize="lg">
    @php
    if (isset($club)) {
        $entity = $club;
        $region = $club->region;
        $downloads = App\Models\ReportDownload::byUserAndType( Auth::user(), 'App\Models\Club', $club->id)->pluck('updated_at','report_id')->toArray();
    } elseif (isset($league)) {
        $entity = $league;
        $region = $league->region;
        $downloads = App\Models\ReportDownload::byUserAndType( Auth::user(), 'App\Models\League', $league->id)->pluck('updated_at','report_id')->toArray();
    } elseif (isset($region)){
        $entity = $region;
        $downloads = App\Models\ReportDownload::byUserAndType( Auth::user(), 'App\Models\Region', $region->id)->pluck('updated_at','report_id')->toArray();
    }

    @endphp
    <div class="card-deck">
        @if ($scope=='club')
            <div class="card border-info my-3">
                <div class="card-header">Vereinsspielpläne</div>
                <div class="card-body text-info">
                    <ul>
                        <li class="card-text">Gesamtplan</li>
                        <li class="card-text">Heimspielplan</li>
                        <li class="card-text">Schiedsrichterplan</li>
                        <li class="card-text">Vereinsrundenpläne</li>
                    </ul>
                    <p class="card-text"> für {{$entity->shortname}}</p>
                    <p class="card-text">Wähle Dein Format...</p>
                    <div >
                        @if ($entity->filecount(App\Enums\ReportFileType::XLSX()) > 0)
                        <a type="button" class="btn btn-outline-primary" href="{{ route('club_archive.get', ['club' => $entity, 'format'=>App\Enums\ReportFileType::XLSX]) }}">
                        {{ App\Enums\ReportFileType::XLSX()->description }}<i class="fas fa-file-download fa-lg mx-2"></i>
                        </a>
                        @endif
                        @if ($entity->filecount(App\Enums\ReportFileType::HTML()) > 0)
                        <a type="button" class="btn btn-outline-secondary" href="{{ route('club_archive.get', ['club' => $entity, 'format'=>App\Enums\ReportFileType::HTML]) }}">
                        {{ App\Enums\ReportFileType::HTML()->description }}<i class="fas fa-file-download fa-lg mx-2"></i>
                        </a>
                        @endif
                        @foreach ( $region->fmt_club_reports->getFlags() as $format )
                        @if ($entity->filecount($format) > 0)
                        <a type="button" class="btn btn-outline-info" href="{{ route('club_archive.get', ['club' => $entity, 'format'=>$format->value]) }}">
                            {{ $format->description }}<i class="fas fa-file-download fa-lg mx-2"></i>
                        </a>
                        @endif
                        @endforeach
                    </div>
                </div>
                <div class="card-footer text-muted">@isset ($downloads[ App\Enums\Report::ClubGames]) Letzter Download am {{ $downloads[ App\Enums\Report::ClubGames]->isoFormat('L LT') }} @else Noch nicht runtergeladen @endisset</div>
            </div>
        @endif
        @if ($scope=='league')
            <div class="card border-info my-3">
                <div class="card-header">Rundenspielplan</div>
                <div class="card-body text-info">
                    <p class="card-text">Rundenspielplan für {{$entity->shortname}}</p>
                    <p class="card-text">Wähle Dein Format...</p>
                    <div>
                        @if ($entity->filecount(App\Enums\ReportFileType::XLSX()) > 0)
                        <a type="button" class="btn btn-outline-primary" href="{{ route('league_archive.get', ['league' => $entity, 'format'=>App\Enums\ReportFileType::XLSX]) }}">
                        {{ App\Enums\ReportFileType::XLSX()->description }}<i class="fas fa-file-download fa-lg mx-2"></i>
                        </a>
                        @endif
                        @if ($entity->filecount(App\Enums\ReportFileType::HTML()) > 0)
                        <a type="button" class="btn btn-outline-secondary" href="{{ route('league_archive.get', ['league' => $entity, 'format'=>App\Enums\ReportFileType::HTML]) }}">
                        {{ App\Enums\ReportFileType::HTML()->description }}<i class="fas fa-file-download fa-lg mx-2"></i>
                        </a>
                        @endif
                        @foreach ( $region->fmt_league_reports->getFlags() as $format )
                        @if ($entity->filecount($format) > 0)
                        <a type="button" class="btn btn-outline-info" href="{{ route('league_archive.get', ['league' => $entity, 'format'=>$format->value]) }}">
                            {{ $format->description }}<i class="fas fa-file-download fa-lg mx-2"></i>
                        </a>
                        @endif
                        @endforeach
                    </div>
                </div>
                <div class="card-footer text-muted">@isset ($downloads[ App\Enums\Report::LeagueGames]) Letzter Download am {{ $downloads[ App\Enums\Report::LeagueGames]->isoFormat('L LT') }} @else Noch nicht runtergeladen @endisset</div>
            </div>
        @endif
        <div class="card border-info my-3">
            <div class="card-header">Addresslisten</div>
            <div class="card-body text-info">
                <p class="card-text">Kontaktdaten der Bezirke (Vereine, Runden)</p>
                <p class="card-text"> für {{$region->code}}</p>
                <div >
                    @if ($region->filecount(App\Enums\ReportFileType::XLSX(), 'Addressbuch') > 0)
                    <a type="button" class="btn btn-outline-primary" href="{{ route('region_members_archive.get', ['region' => $region, 'format'=>App\Enums\ReportFileType::XLSX]) }}">
                    {{ App\Enums\ReportFileType::XLSX()->description }}<i class="fas fa-file-download fa-lg mx-2"></i>
                    </a>
                    @endif
                    @if ($region->filecount(App\Enums\ReportFileType::HTML(), 'Addressbuch') > 0)
                    <a type="button" class="btn btn-outline-secondary" href="{{ route('region_members_archive.get', ['region' => $region, 'format'=>App\Enums\ReportFileType::HTML]) }}">
                    {{ App\Enums\ReportFileType::HTML()->description }}<i class="fas fa-file-download fa-lg mx-2"></i>
                    </a>
                    @endif
                    @foreach ( $region->fmt_league_reports->getFlags() as $format )
                    @if ($region->filecount($format, 'Addressbuch') > 0)
                    <a type="button" class="btn btn-outline-info" href="{{ route('region_members_archive.get', ['region' => $region, 'format'=>$format->value]) }}">
                        {{ $format->description }}<i class="fas fa-file-download fa-lg mx-2"></i>
                    </a>
                    @endif
                    @endforeach
                </div>
            </div>
            <div class="card-footer text-muted">@isset ($downloads[ App\Enums\Report::AddressBook]) Letzter Download am {{ $downloads[ App\Enums\Report::AddressBook]->isoFormat('L LT') }} @else Noch nicht runtergeladen @endisset</div>
        </div>
        @if ($scope=='region')
        <div class="card border-info my-3">
            <div class="card-header">Bezirksplan</div>
            <div class="card-body text-info">
                <p class="card-text">Gesamtplan</p>
                <p class="card-text"> für {{$region->code}}</p>
                <div >
                    @if ($region->filecount(App\Enums\ReportFileType::XLSX(), 'Gesamtplan') > 0)
                    <a type="button" class="btn btn-outline-primary" href="{{ route('region_archive.get', ['region' => $region, 'format'=>App\Enums\ReportFileType::XLSX]) }}">
                    {{ App\Enums\ReportFileType::XLSX()->description }}<i class="fas fa-file-download fa-lg mx-2"></i>
                    </a>
                    @endif
                    @if ($region->filecount(App\Enums\ReportFileType::HTML(), 'Gesamtplan') > 0)
                    <a type="button" class="btn btn-outline-secondary" href="{{ route('region_archive.get', ['region' => $region, 'format'=>App\Enums\ReportFileType::HTML]) }}">
                    {{ App\Enums\ReportFileType::HTML()->description }}<i class="fas fa-file-download fa-lg mx-2"></i>
                    </a>
                    @endif
                    @foreach ( $region->fmt_league_reports->getFlags() as $format )
                    @if ($region->filecount($format, 'Gesamtplan') > 0)
                    <a type="button" class="btn btn-outline-info" href="{{ route('region_archive.get', ['region' => $region, 'format'=>$format->value]) }}">
                        {{ $format->description }}<i class="fas fa-file-download fa-lg mx-2"></i>
                    </a>
                    @endif
                    @endforeach
                </div>
            </div>
            <div class="card-footer text-muted">@isset ($downloads[ App\Enums\Report::RegionGames]) Letzter Download am {{ $downloads[ App\Enums\Report::RegionGames]->isoFormat('L LT') }} @else Noch nicht runtergeladen @endisset</div>
        </div>
        @endif
        <div class="card border-info my-3">
            <div class="card-header">Bezirks-Rundenpläne</div>
            <div class="card-body text-info">
                <p class="card-text">Alle Rundenpläne mit MVen</p>
                <p class="card-text"> für {{$region->code}}</p>
                <div>
                    @if ( ($region->filecount(App\Enums\ReportFileType::XLSX(), 'Rundenbuch') + ($region->league_filecount(App\Enums\ReportFileType::XLSX()))) > 0)
                    <a type="button" class="btn btn-outline-primary" href="{{ route('region_league_archive.get', ['region' => $region, 'format'=>App\Enums\ReportFileType::XLSX]) }}">
                    {{ App\Enums\ReportFileType::XLSX()->description }}<i class="fas fa-file-download fa-lg mx-2"></i>
                    </a>
                    @endif
                    @if ( ($region->filecount(App\Enums\ReportFileType::HTML(), 'Rundenbuch') + ($region->league_filecount(App\Enums\ReportFileType::HTML()))) > 0)
                    <a type="button" class="btn btn-outline-secondary" href="{{ route('region_league_archive.get', ['region' => $region, 'format'=>App\Enums\ReportFileType::HTML]) }}">
                    {{ App\Enums\ReportFileType::HTML()->description }}<i class="fas fa-file-download fa-lg mx-2"></i>
                    </a>
                    @endif
                    @foreach ( $region->fmt_league_reports->getFlags() as $format )
                    @if ( ($region->filecount($format, 'Rundenbuch') + ($region->league_filecount($format))) > 0)
                    <a type="button" class="btn btn-outline-info" href="{{ route('region_league_archive.get', ['region' => $region, 'format'=>$format->value]) }}">
                        {{ $format->description }}<i class="fas fa-file-download fa-lg mx-2"></i>
                    </a>
                    @endif
                    @endforeach
                </div>
            </div>
            <div class="card-footer text-muted">@isset ($downloads[ App\Enums\Report::LeagueBook]) Letzter Download am {{ $downloads[ App\Enums\Report::LeagueBook]->isoFormat('L LT') }} @else Noch nicht runtergeladen @endisset</div>
        </div>
        @if ($scope=='region')
            <div class="card border-info my-3">
                <div class="card-header">TeamSL</div>
                <div class="card-body text-info">
                    <h5 class="card-title">Import Dateien</h5>
                    <ul>
                        <li class="card-text">Teams.csv</li>
                        <li class="card-text">Games.csv</li>
                    </ul>
                    <p class="card-text"> für {{$region->code}}</p>
                    @if ($region->teamware_filecount() > 0)
                    <a type="button" class="btn btn-outline-primary" href="{{ route('region_teamware_archive.get', ['region' => $region]) }}">
                        {{ App\Enums\ReportFileType::CSV()->description }}<i class="fas fa-file-download fa-lg mx-2"></i>
                    </a>
                    @endif
                </div>
                <div class="card-footer text-muted">@isset ($downloads[ App\Enums\Report::Teamware]) Letzter Download am {{ $downloads[ App\Enums\Report::Teamware]->isoFormat('L LT') }} @else Noch nicht runtergeladen @endisset</div>
            </div>
        @endif
</x-modal-help>
